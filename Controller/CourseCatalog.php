<?php
require_once __DIR__ . '/../Models/Database.php';
require_once __DIR__ . '/../Models/Child.php';
require_once __DIR__ . '/../Models/Course.php';
require_once __DIR__ . '/../Models/Parent.php';

$Parent = new Parents();
if(isset($_GET['name']))
{
    $parent_name = htmlspecialchars($_GET['name']);
    $parent = $Parent->GetParentByName($parent_name);
}
if($parent)
{
    $Child = new Child();
    $children = $Child->GetChildrenByParentId($parent->GetUserID());
    if($children)
    {
            $age = $children->GetAge();
            $Course = new Course();
            $List = $Course->GetCoursesByAge($age);
    }
}
if (!empty($List)) {
    $parent_id = $parent->getId();
    $_SESSION['course_list'] = $List;
    $_SESSION['child_age']   = $age;
    header("Location: /Courses.php");
    exit();
}



