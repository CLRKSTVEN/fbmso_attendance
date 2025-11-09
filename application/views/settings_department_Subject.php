<!-- settings_department_Subject.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Subjects</title>
    <!-- Add your stylesheets and scripts here -->
</head>
<body>
    <div class="container">
        <h1>Subjects for <?= isset($course) ? $course->CourseDescription : 'Selected Course' ?></h1>
        
        <!-- Check if data is available -->
        <?php if (!empty($data)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Subject Code</th>
                        <th>Subject Name</th>
                        <th>Major</th>
                        <th>Year Level</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $course_subject): ?>
                        <tr>
                            <td><?= $course_subject->SubjectCode; ?></td>
                            <td><?= $course_subject->SubjectName; ?></td>
                            <td><?= $course_subject->Major; ?></td>
                            <td><?= $course_subject->YearLevel; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No subjects found for this course.</p>
        <?php endif; ?>

        <!-- Year Level Selection -->
        <h2>Select Year Level</h2>
        <form method="get" action="<?= base_url('Settings/displaysubByCourse/' . $courseid); ?>">
            <select name="year_level">
                <?php if (!empty($yearLevels)): ?>
                    <?php foreach ($yearLevels as $level): ?>
                        <option value="<?= $level->id; ?>"><?= $level->name; ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            <button type="submit">Filter</button>
        </form>
    </div>
</body>
</html>
