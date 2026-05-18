<?php
session_start();

require_once '/../Models/Database.php';
require_once '/../Models/Child.php';
require_once '/../Models/Course.php';
require_once '/../Models/Parent.php';

if (isset($_GET['name'])) {
    $parent_name = htmlspecialchars($_GET['name']);

    $Parent = new Parents();
    $parent = $Parent->GetParentByName($parent_name);

    if ($parent) {
        $Child    = new Child();
        $children = $Child->GetChildrenByParentId($parent->GetUserID());

        if ($children) {
            $age    = $children->GetAge();
            $Course = new Course();
            $List   = $Course->GetCoursesByAge($age);

            $_SESSION['course_list'] = $List;
            $_SESSION['child_age']   = $age;
            $_SESSION['parent_id']   = $parent->GetUserID();

            header("Location: /Courses.php");
            exit();
        }
    }
}

header("Location: /Courses.php?error=not_found");
exit();