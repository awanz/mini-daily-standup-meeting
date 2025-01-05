<?php
require_once 'BaseController.php';

class CalendarController extends BaseController
{
    public function index()
    {
        // die('adasd');
        $this->render('calendar/index');
    }
}
