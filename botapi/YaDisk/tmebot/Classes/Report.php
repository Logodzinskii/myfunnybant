<?php

class Report
{

    public function setSessionForReport($numberMessage,$reportMonth,$reportSum,$reportType)
    {

        session_start();

        $_SESSION['numberMessage'] = $numberMessage;
        $_SESSION['reportMonth'] = $reportMonth;
        $_SESSION['reportSum'] = $reportSum;
        $_SESSION['reportType'] =$reportType;
    }

}
