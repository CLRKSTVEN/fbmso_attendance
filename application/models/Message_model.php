<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Message_model extends CI_Model {

public function getUnreadMessages($receiverID) {
    $this->db->select('m.*, o.name AS sender_name');
    $this->db->from('messages m');
    $this->db->join('o_users o', 'o.IDNumber = m.sender_id', 'left');
    $this->db->where('m.receiver_id', $receiverID);
    $this->db->where('m.status', 'unread');
    $this->db->order_by('m.timestamp', 'DESC');
    $this->db->limit(5);
    return $this->db->get()->result();
}

public function getConversation($userID, $otherID) {
 $this->db->select('m.*, 
    CASE 
        WHEN ou.name IS NOT NULL AND ou.name != "" THEN ou.name
        WHEN ou.fName IS NOT NULL AND ou.fName != "" THEN CONCAT(ou.fName, " ", IF(ou.mName != "", CONCAT(LEFT(ou.mName, 1), ". "), ""), ou.lName)
        WHEN sp.FirstName IS NOT NULL THEN CONCAT(sp.FirstName, " ", sp.LastName)
        WHEN st.FirstName IS NOT NULL THEN CONCAT(st.FirstName, " ", st.LastName)
        ELSE "Someone :("
    END AS sender_name,
    COALESCE(ou.avatar, sp.imagePath, "avatar.png") AS avatar');

    $this->db->from('messages m');
    $this->db->join('o_users ou', 'ou.IDNumber = m.sender_id', 'left');
    $this->db->join('studeprofile sp', 'sp.StudentNumber = m.sender_id', 'left');
    $this->db->join('staff st', 'st.IDNumber = m.sender_id', 'left');
    $this->db->where("(m.sender_id = '$userID' AND m.receiver_id = '$otherID') OR (m.sender_id = '$otherID' AND m.receiver_id = '$userID')");
    $this->db->order_by('m.timestamp', 'ASC');
    return $this->db->get()->result();
}

public function sendMessage($sender_id, $receiver_id, $message) {
    $data = [
        'sender_id' => $sender_id,
        'receiver_id' => $receiver_id,
        'message' => base64_encode($message),
        'timestamp' => date('Y-m-d H:i:s'),
        'status' => 'sent',
        'seen' => 0,
    ];
    $this->db->insert('messages', $data);
}

public function markAsRead($receiverID, $senderID) {
    $this->db->where("(sender_id = '$receiverID' OR receiver_id = '$receiverID')");
    $this->db->where('sender_id', $senderID);
    $this->db->update('messages', [
        'status' => 'read',
        'seen' => 1
    ]);
}

public function get_all_users($excludeID = null) {
    $usersMap = [];

    // Fetch students
    $students = $this->db->select("
            StudentNumber as IDNumber, 
            CONCAT(FirstName, ' ', 
                   IF(MiddleName != '', CONCAT(LEFT(MiddleName, 1), '. '), ''), 
                   LastName,
                   IF(NameExtn != '', CONCAT(' ', NameExtn), '')
            ) as name,
            'Student' as position,
            COALESCE(NULLIF(imagePath, ''), 'avatar.png') as avatar
        ")
        ->from('studeprofile');

    if ($excludeID) $students->where('StudentNumber !=', $excludeID);
    $students = $students->get()->result();

    foreach ($students as $s) {
        $usersMap[$s->IDNumber] = $s;
    }

    // Fetch o_users (admins)
    $admins = $this->db->select("
        IDNumber, 
        CASE 
            WHEN name = '' THEN CONCAT(fName, ' ', IF(mName != '', CONCAT(LEFT(mName, 1), '. '), ''), lName)
            ELSE name
        END AS name,
        position,
        COALESCE(NULLIF(avatar, ''), 'avatar.png') as avatar
    ")
    ->from('o_users')
    ->where('acctStat', 'active');

    if ($excludeID) $admins->where('IDNumber !=', $excludeID);
    $admins = $admins->get()->result();

    foreach ($admins as $a) {
        // Overwrite only if not already set (i.e., keep o_users as higher priority)
        $usersMap[$a->IDNumber] = $a;
    }

    // Fetch staff
    $staff = $this->db->select("
        IDNumber, 
        CONCAT(FirstName, ' ', LastName) AS name,
        'Staff' as position,
        'avatar.png' as avatar
    ")
    ->from('staff');

    if ($excludeID) $staff->where('IDNumber !=', $excludeID);
    $staff = $staff->get()->result();

    foreach ($staff as $st) {
        if (!isset($usersMap[$st->IDNumber])) {
            $usersMap[$st->IDNumber] = $st;
        }
    }

    return array_values($usersMap); // reset index
}



public function getLastReadMessage($receiverID, $senderID) {
    $this->db->select('timestamp');
    $this->db->from('messages');
    $this->db->where('sender_id', $senderID);
    $this->db->where("(sender_id = '$receiverID' OR receiver_id = '$receiverID')");
    $this->db->where('status', 'read');
    $this->db->order_by('timestamp', 'DESC');
    $this->db->limit(1);
    return $this->db->get()->row();
}

public function setTyping($sender, $receiver) {
    $now = date('Y-m-d H:i:s');
    $this->db->replace('typing_status', [
        'sender_id' => $sender,
        'receiver_id' => $receiver,
        'is_typing' => 1,
        'updated_at' => $now
    ]);
}

public function stopTyping($sender, $receiver) {
    $this->db->where('sender_id', $sender);
    $this->db->where('receiver_id', $receiver);
    $this->db->update('typing_status', ['is_typing' => 0]);
}

public function isTyping($receiver, $sender) {
    $this->db->where('sender_id', $sender);
    $this->db->where('receiver_id', $receiver);
    $this->db->where('is_typing', 1);
    $this->db->where('updated_at >=', date('Y-m-d H:i:s', strtotime('-5 seconds')));
    $query = $this->db->get('typing_status');
    return $query->num_rows() > 0;
}

public function getUnreadCount($userID) {
    $this->db->where('receiver_id', $userID);
    $this->db->where('is_read', 0);
    return $this->db->count_all_results('messages');
}

public function has_new_messages($userID) {
    $this->db->where('receiver_id', $userID);
    $this->db->where('is_read', 0);
    $query = $this->db->get('messages');
    return $query->num_rows() > 0;
}

public function has_unseen_messages($receiver_id, $sender_id) {
    $this->db->where('receiver_id', $receiver_id);
    $this->db->where('sender_id', $sender_id);
    $this->db->where('seen', 0);
    return $this->db->count_all_results('messages') > 0;
}

public function getRecentConversations($receiverID) {
    $sub = $this->db->select('
            CASE 
                WHEN sender_id = '.$this->db->escape($receiverID).' THEN receiver_id 
                ELSE sender_id 
            END as other_user_id,
            MAX(timestamp) as latest')
        ->from('messages')
        ->where("(sender_id = '$receiverID' OR receiver_id = '$receiverID')", NULL, FALSE)
        ->group_by('other_user_id')
        ->get_compiled_select();

    $this->db->select('m.*, 
        CASE 
            WHEN m.sender_id = '.$this->db->escape($receiverID).' THEN u2.name 
            ELSE u1.name 
        END as name,
        CASE 
            WHEN m.sender_id = '.$this->db->escape($receiverID).' THEN u2.avatar 
            ELSE u1.avatar 
        END as avatar');
    $this->db->from('messages m');
    $this->db->join("($sub) latest", '((m.sender_id = '.$this->db->escape($receiverID).' AND m.receiver_id = latest.other_user_id) OR (m.receiver_id = '.$this->db->escape($receiverID).' AND m.sender_id = latest.other_user_id)) AND m.timestamp = latest.latest');
    $this->db->join('o_users u1', 'u1.IDNumber = m.sender_id', 'left');
    $this->db->join('o_users u2', 'u2.IDNumber = m.receiver_id', 'left');
    $this->db->order_by('m.timestamp', 'DESC');

    $result = $this->db->get()->result();

    foreach ($result as &$row) {
        date_default_timezone_set('Asia/Manila');
        $row->timestamp = date('M j, g:i A', strtotime($row->timestamp));
        $decoded = base64_decode($row->message, true);
        $row->message = $decoded !== false ? $decoded : $row->message;
    }

    return $result;
}

public function editMessage($id, $newText, $senderID) {
    $this->db->where(['id' => $id, 'sender_id' => $senderID]);
    return $this->db->update('messages', ['message' => $newText]);
}

public function deleteMessage($id, $senderID) {
    $this->db->where(['id' => $id, 'sender_id' => $senderID]);
    return $this->db->delete('messages');
}

public function searchUsers($query) {
    $query = $this->db->escape_like_str($query);

    $students = $this->db->query("
        SELECT s.StudentNumber AS IDNumber, 
               CONCAT(s.FirstName, ' ', s.LastName) AS name, 
               'Student' AS position,
               COALESCE(NULLIF(s.imagePath, ''), 'avatar.png') AS avatar
        FROM studeprofile s
        WHERE (s.FirstName LIKE '%$query%' OR s.LastName LIKE '%$query%' OR CONCAT(s.FirstName, ' ', s.LastName) LIKE '%$query%')
        LIMIT 10
    ")->result();

    $staff = $this->db->query("
        SELECT st.IDNumber, 
               CONCAT(st.FirstName, ' ', st.LastName) AS name, 
               'Staff' AS position,
               'avatar.png' AS avatar
        FROM staff st
        WHERE (st.FirstName LIKE '%$query%' OR st.LastName LIKE '%$query%' OR CONCAT(st.FirstName, ' ', st.LastName) LIKE '%$query%')
        LIMIT 10
    ")->result();

    $o_users = $this->db->query("
        SELECT IDNumber,
               CASE 
                 WHEN name = '' THEN CONCAT(fName, ' ', lName)
                 ELSE name
               END AS name,
               position,
               COALESCE(NULLIF(avatar, ''), 'avatar.png') AS avatar
        FROM o_users
        WHERE acctStat = 'active'
        AND (
            name LIKE '%$query%' 
            OR CONCAT(fName, ' ', lName) LIKE '%$query%' 
            OR position LIKE '%$query%'
        )
        LIMIT 10
    ")->result();

    $users = $this->db->query("
        SELECT username AS IDNumber, 
               name, 
               position, 
               COALESCE(NULLIF(avatar, ''), 'avatar.png') AS avatar
        FROM users
        WHERE acctStat = 'active'
        AND (name LIKE '%$query%' OR fName LIKE '%$query%' OR lName LIKE '%$query%')
        LIMIT 10
    ")->result();

    $active = $this->db->query("
        SELECT IDNumber FROM online_users
        WHERE last_activity >= NOW() - INTERVAL 3 MINUTE
    ")->result();

    $onlineIDs = array_column($active, 'IDNumber');

    // Merge all users into one array
    $all = array_merge($students, $staff, $o_users, $users);

    // Remove duplicates based on IDNumber
    $unique = [];
    foreach ($all as $u) {
        if (!isset($unique[$u->IDNumber])) {
            $u->isOnline = in_array($u->IDNumber, $onlineIDs) ? 1 : 0;
            $unique[$u->IDNumber] = $u;
        }
    }

    // Return as array (not associative)
    return array_values($unique);
}

}
