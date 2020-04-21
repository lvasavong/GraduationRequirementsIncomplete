<?php
//*******************************************************************************************************
//	m_requirements-incomplete.php  -- Processing for submission of requirements-incomplete Forms.
//
//	Author: Linda Vasavong
//	Date Created: ????
//	Date Modified: ???
//*******************************************************************************************************

//===================================================================
// Initialization
//===================================================================
require_once('class_library/LoginForm.php');
require_once('class_library/Common.php');
require_once('class_library/database_drivers/MySQLDriver.php');
require_once('class_library/database_drivers/OnBaseDriver.php');
require_once('class_library/database_drivers/DARSDriver.php');

session_start();
session_name('requirements-incomplete');

$loginForm = new LoginForm("Requirements Incomplete Form");
$common = new Common();

$validTest = true;

$dump = "";
$userStatus = "";
$majors;

$formData = array();
$errors = array();
$error_messages = array();
$userData = $_SESSION['UserData'];

//===================================================================
// Request Handling
//===================================================================

if($_SESSION['Processed'] == 'Yes')
{
	//Catch to eliminate duplicate submissions
	unset($_SESSION['Processed']);
	
	// Redirect to the login page
	if($_SERVER['SERVER_NAME'] == 'secure1.wdev.rochester.edu')
		header('Location: https://secure1.wdev.rochester.edu/ccas/requirements-incomplete.php');
	else
		header('Location: https://secure1.rochester.edu/ccas/requirements-incomplete.php');
}
else if(isset($_POST['Login']))
{
	$loginForm->Instantiate($_POST['username'], $_POST['password']);
	$loginForm->Validate();
	
	if($loginForm->IsValid())
	{
		$userData = $loginForm->GetInfo();

		$dars_drvr = new DARSDriver();

		$majors = $dars_drvr->GetStudentMajors($userData['studentID']);
		$majorsList;
		$tracker = 0;
		foreach($majors as $major)
		{
			if($tracker == 0)
			{
				$majorsList = $major;
			}
			else
			{
				$majorsList .= "," . $major;
			}
			$tracker++;
		}

		$majorsList = str_replace(' ', '', $majorsList);

		$minors = $dars_drvr->GetStudentMinors($userData['studentID']);
		$minorsList;
		$tracker = 0;
		foreach($minors as $minor)
		{
			if($tracker == 0)
			{
				$minorsList = $minor;
			}
			else
			{
				$minorsList .= "," . $minor;
			}
			$tracker++;
		}
		$minorsList = str_replace(' ', '', $minorsList);

		$clusters = $dars_drvr->GetStudentClusters($userData['studentID']);
		$clustersList;
		$tracker = 0;
		foreach($clusters as $cluster)
		{
			if($tracker == 0)
			{
				$clustersList = $cluster;
			}
			else
			{
				$clustersList .= "," . $cluster;
			}
			$tracker++;
		}
		$clustersList = str_replace(' ', '', $clustersList);

		$userData['majors'] = $majorsList;
		$userData['minors'] = $minorsList;
		$userData['clusters'] = $clustersList;

		$_SESSION['UserData'] = $userData;

		$_SESSION['LoggedIn'] = 'Yes';
    }
}
else if(isset($_POST['Save']) && ($_SESSION['LoggedIn'] == 'Yes'))
{
	$formData = $_POST;

	if(!empty($userData['firstName']) && !empty($userData['lastName']) && !empty($userData['studentID']))	//check if userdata timed out
	{
		$errors = Validate($formData);
		
		if(empty($errors) && empty($error_messages))
		{
			if(Process($formData))
			{
				$status = "OK";
				SendEmail($formData,date('Y-m-d H:i:s', time()));
				unset($_SESSION['LoggedIn']);
				unset($_SESSION['UserData']);
				unset($_SESSION['State']);
				$_SESSION['Processed'] = 'Yes';
			}
			else
			{
				$status = "DB_ERR";
			}			
		}
		else
		{
			$validTest = false;
		}
	}
	else
	{
		unset($_SESSION['LoggedIn']);
		unset($_SESSION['UserData']);
		unset($_SESSION['State']);
	}
}
else
{
	unset($_SESSION['LoggedIn']);
	unset($_SESSION['UserData']);
	unset($_SESSION['State']);
}

//===================================================================
// Functions
//===================================================================
//-------------------------------------------------------------------
function Validate($data)
{
	global $error_messages;
	$errors = array();

	if(empty($data['learningYes']) && empty($data['learningNo']) && empty($data['spokenYes']))
    {
        $errors[] = "learning";
        $error_messages[] = "Please indicate a response about your interest in learning more about the ninth and/or tenth-semester process.";
	}

    if(empty($data['artsYes']) && empty($data['hajimYes']) && empty($data['eastmanYes']))
    {
        $errors[] = "school";
        $error_messages[] = "Please choose a college where you are completing the requirements for your degree.";
	}
	else if(!empty($data['eastmanYes']) && empty($data['artsYes']) && empty($data['hajimYes']))
	{
		$errors[] = "school";
		$error_messages[] = "You have indicated that you are completing the requirements for a Dual Degree at Eastman. You must also indicate another college for your dual degree.";
	}

	if(empty($data['studentFirstName']))
		$errors[] = "studentFirstName";
	if(empty($data['studentLastName']))
		$errors[] = "studentLastName";
	if(empty($data['studentID']))
		$errors[] = "studentID";
	if(empty($data['emailAddress']))
		$errors[] = "emailAddress";
	if(empty($data['permAddress']))
		$errors[] = "permAddress";
	if(empty($data['phoneNumber']))
		$errors[] = "phoneNumber";
	if(empty($data['major']))
		$errors[] = "major";
	if(empty($data['cluster']))
		$errors[] = "cluster";

	if(empty($data['changeYes']) && empty($data['changeNo']))
	{
		$errors[] = "change";
		$error_messages[] = "Please indicate whether or not you need to make a change to your Major(s), Minor(s), Cluster(s) based on what is listed.";
	}
		
	if(empty($data['circumstances']))
		$errors[] = "circumstances";

	$cnt = 0;
	
	if(!empty($data['courseRationale1']))
	{
		if(empty($data['courseNumber1']) || 
			empty($data['courseTitle1']) ||
			empty($data['courseWhere1']) ||
			empty($data['courseStart1']))
			{
					$cnt++;
			}
    }
	if(!empty($data['courseNumber1']))
	{
		if(empty($data['courseTitle1']) || 
			empty($data['courseRationale1']) ||
			empty($data['courseWhere1']) ||
			empty($data['courseStart1']))
			{
					$cnt++;
			}
    }
    if(!empty($data['courseTitle1']))
	{
		if(empty($data['courseNumber1']) || 
			empty($data['courseRationale1']) ||
			empty($data['courseWhere1']) ||
			empty($data['courseStart1']))
			{
					$cnt++;
			}
    }
    if(!empty($data['courseWhere1']))
	{
		if(empty($data['courseNumber1']) || 
			empty($data['courseRationale1']) ||
			empty($data['courseTitle1']) ||
			empty($data['courseStart1']))
			{
					$cnt++;
			}
    }
    if(!empty($data['courseStart1']))
	{
		if(empty($data['courseNumber1']) || 
			empty($data['courseRationale1']) ||
			empty($data['courseTitle1']) ||
			empty($data['courseWhere1']))
			{
					$cnt++;
			}
	}
	
	if(!empty($data['courseRationale2']))
	{
		if(empty($data['courseNumber2']) || 
			empty($data['courseTitle2']) ||
			empty($data['courseWhere2']) ||
			empty($data['courseStart2']))
			{
					$cnt++;
			}
    }
    if(!empty($data['courseNumber2']))
	{
		if(empty($data['courseTitle2']) ||
			empty($data['courseRationale2']) ||
			empty($data['courseWhere2']) ||
			empty($data['courseStart2']))
			{
					$cnt++;
			}
    }
    if(!empty($data['courseTitle2']))
	{
		if(empty($data['courseNumber2']) ||
			empty($data['courseRationale2']) || 
			empty($data['courseWhere2']) ||
			empty($data['courseStart2']))
			{
					$cnt++;
			}
    }
    if(!empty($data['courseWhere2']))
	{
		if(empty($data['courseNumber2']) ||
			empty($data['courseRationale2']) || 
			empty($data['courseTitle2']) ||
			empty($data['courseStart2']))
			{
					$cnt++;
			}
    }
    if(!empty($data['courseStart2']))
	{
		if(empty($data['courseNumber2']) ||
			empty($data['courseRationale2']) || 
			empty($data['courseTitle2']) ||
			empty($data['courseWhere2']))
			{
					$cnt++;
			}
	}
    
    if(!empty($data['courseRationale3']))
	{
		if(empty($data['courseNumber3']) ||
			empty($data['courseTitle3']) ||
			empty($data['courseWhere3']) ||
			empty($data['courseStart3']))
			{
					$cnt++;
			}
	}
	if(!empty($data['courseNumber3']))
	{
		if(empty($data['courseRationale3']) ||
			empty($data['courseTitle3']) ||
			empty($data['courseWhere3']) ||
			empty($data['courseStart3']))
			{
					$cnt++;
			}
    }
    if(!empty($data['courseTitle3']))
	{
		if(empty($data['courseNumber3']) ||
			empty($data['courseRationale3']) ||
			empty($data['courseWhere3']) ||
			empty($data['courseStart3']))
			{
					$cnt++;
			}
    }
    if(!empty($data['courseWhere3']))
	{
		if(empty($data['courseNumber3']) ||
			empty($data['courseRationale3']) ||
			empty($data['courseTitle3']) ||
			empty($data['courseStart3']))
			{
					$cnt++;
			}
    }
    if(!empty($data['courseStart3']))
	{
		if(empty($data['courseNumber3']) ||
			empty($data['courseRationale3']) ||
			empty($data['courseTitle3']) ||
			empty($data['courseWhere3']))
			{
					$cnt++;
			}
	}
    
    if(!empty($data['courseRationale4']))
	{
		if(empty($data['courseNumber4']) ||
			empty($data['courseTitle4']) ||
			empty($data['courseWhere4']) ||
			empty($data['courseStart4']))
			{
					$cnt++;
			}
	}
	if(!empty($data['courseNumber4']))
	{
		if(empty($data['courseRationale4']) ||
			empty($data['courseTitle4']) ||
			empty($data['courseWhere4']) ||
			empty($data['courseStart4']))
			{
					$cnt++;
			}
    }
    if(!empty($data['courseTitle4']))
	{
		if(empty($data['courseNumber4']) ||
			empty($data['courseRationale4']) ||
			empty($data['courseWhere4']) ||
			empty($data['courseStart4']))
			{
					$cnt++;
			}
    }
    if(!empty($data['courseWhere4']))
	{
		if(empty($data['courseNumber4']) ||
			empty($data['courseRationale4']) ||
			empty($data['courseTitle4']) ||
			empty($data['courseStart4']))
			{
					$cnt++;
			}
    }
    if(!empty($data['courseStart4']))
	{
		if(empty($data['courseNumber4']) ||
			empty($data['courseRationale4']) ||
			empty($data['courseTitle4']) ||
			empty($data['courseWhere4']))
			{
					$cnt++;
			}
	}
    
    if(!empty($data['courseRationale5']))
	{
		if(empty($data['courseNumber5']) ||
			empty($data['courseTitle5']) ||
			empty($data['courseWhere5']) ||
			empty($data['courseStart5']))
			{
					$cnt++;
			}
	}
	if(!empty($data['courseNumber5']))
	{
		if(empty($data['courseRationale5']) ||
			empty($data['courseTitle5']) ||
			empty($data['courseWhere5']) ||
			empty($data['courseStart5']))
			{
					$cnt++;
			}
    }
    if(!empty($data['courseTitle5']))
	{
		if(empty($data['courseNumber5']) ||
			empty($data['courseRationale5']) ||
			empty($data['courseWhere5']) ||
			empty($data['courseStart5']))
			{
					$cnt++;
			}
    }
    if(!empty($data['courseWhere5']))
	{
		if(empty($data['courseNumber5']) ||
			empty($data['courseRationale5']) ||
			empty($data['courseTitle5']) ||
			empty($data['courseStart5']))
			{
					$cnt++;
			}
    }
    if(!empty($data['courseStart5']))
	{
		if(empty($data['courseNumber5']) ||
			empty($data['courseRationale5']) ||
			empty($data['courseTitle5']) ||
			empty($data['courseWhere5']))
			{
					$cnt++;
			}
	}
	
	if(!empty($data['courseRationale6']))
	{
		if(empty($data['courseNumber6']) ||
			empty($data['courseTitle6']) ||
			empty($data['courseWhere6']) ||
			empty($data['courseStart6']))
			{
					$cnt++;
			}
    }
    if(!empty($data['courseNumber6']))
	{
		if(empty($data['courseTitle6']) ||
			empty($data['courseRationale6']) ||
			empty($data['courseWhere6']) ||
			empty($data['courseStart6']))
			{
					$cnt++;
			}
    }
    if(!empty($data['courseTitle6']))
	{
		if(empty($data['courseNumber6']) ||
			empty($data['courseRationale6']) ||
			empty($data['courseWhere6']) ||
			empty($data['courseStart6']))
			{
					$cnt++;
			}
    }
    if(!empty($data['courseWhere6']))
	{
		if(empty($data['courseNumber6']) ||
			empty($data['courseRationale6']) ||
			empty($data['courseTitle6']) ||
			empty($data['courseStart6']))
			{
					$cnt++;
			}
    }
    if(!empty($data['courseStart6']))
	{
		if(empty($data['courseNumber6']) ||
			empty($data['courseRationale6']) ||
			empty($data['courseTitle6']) ||
			empty($data['courseWhere6']))
			{
					$cnt++;
			}
	}
	
	if(!empty($data['courseRationale7']))
	{
		if(empty($data['courseNumber7']) ||
			empty($data['courseTitle7']) ||
			empty($data['courseWhere7']) ||
			empty($data['courseStart7']))
			{
					$cnt++;
			}
    }
    if(!empty($data['courseNumber7']))
	{
		if(empty($data['courseTitle7']) ||
			empty($data['courseRationale7']) ||
			empty($data['courseWhere7']) ||
			empty($data['courseStart7']))
			{
					$cnt++;
			}
    }
    if(!empty($data['courseTitle7']))
	{
		if(empty($data['courseNumber7']) ||
			empty($data['courseRationale7']) ||
			empty($data['courseWhere7']) ||
			empty($data['courseStart7']))
			{
					$cnt++;
			}
    }
    if(!empty($data['courseWhere7']))
	{
		if(empty($data['courseNumber7']) ||
			empty($data['courseRationale7']) ||
			empty($data['courseTitle7']) ||
			empty($data['courseStart7']))
			{
					$cnt++;
			}
    }
    if(!empty($data['courseStart7']))
	{
		if(empty($data['courseNumber7']) ||
			empty($data['courseRationale7']) ||
			empty($data['courseTitle7']) ||
			empty($data['courseWhere7']))
			{
					$cnt++;
			}
	}
	
	if(!empty($data['courseRationale8']))
	{
		if(empty($data['courseNumber8']) ||
			empty($data['courseTitle8']) ||
			empty($data['courseWhere8']) ||
			empty($data['courseStart8']))
			{
					$cnt++;
			}
    }
    if(!empty($data['courseNumber8']))
	{
		if(empty($data['courseTitle8']) ||
			empty($data['courseRationale8']) ||
			empty($data['courseWhere8']) ||
			empty($data['courseStart8']))
			{
					$cnt++;
			}
    }
    if(!empty($data['courseTitle8']))
	{
		if(empty($data['courseNumber8']) ||
			empty($data['courseRationale8']) ||
			empty($data['courseWhere8']) ||
			empty($data['courseStart8']))
			{
					$cnt++;
			}
    }
    if(!empty($data['courseWhere8']))
	{
		if(empty($data['courseNumber8']) ||
			empty($data['courseRationale8']) ||
			empty($data['courseTitle8']) ||
			empty($data['courseStart8']))
			{
					$cnt++;
			}
    }
    if(!empty($data['courseStart8']))
	{
		if(empty($data['courseNumber8']) ||
			empty($data['courseRationale8']) ||
			empty($data['courseTitle8']) ||
			empty($data['courseWhere8']))
			{
					$cnt++;
			}
	}
    
    if(empty($data['courseRationale1']) || empty($data['courseNumber1']) || empty($data['courseTitle1']) || empty($data['courseWhere1']) || empty($data['courseStart1']))
    {
        $errors[] = "courseRow1";
        $error_messages[] = "Please fill out at least the first course row.";
    }

	if($cnt > 0)
	{
		$error_messages[] = "You must complete entire course row for the course(s) you have entered.";
		$errors[] = 'courseRow';
	}
	
	if(empty($data['facultyYes']) && empty($data['facultyNo']))
	{
		$errors[] = "facultyYes";
		$error_messages[] = "Please indicate if you have spoken to your department approval/undergraduate coordinator about your course plan.";
	}
    
	if(!empty($data['facultyYes']))
    {
		if(empty($data['facultyFirstName'])) {
			$errors[] = "facultyFirstName";
		}
		if(empty($data['facultyLastName'])) {
			$errors[] = "facultyLastName";
		}
    }

	return $errors;
}
//-------------------------------------------------------------------
function Process($data)
{	
	$db_drvr = new MySQLDriver();
	
	//strips all parenthesis and dashes from phone number to ensure submission
	$phone = preg_replace("/[^0-9]/", "", $data['phoneNumber']);
		
	/* Submit this record to MySQL */
	$record = array();

	foreach($data as $key => $value)
	{
		/* STRIP OUT ANY KEYS YOU'RE NOT SENDING TO THE MYSQL TABLE */
		if($key != 'Save' && $key != 'circumstancesCount' && $key != 'circumstancesPart2Count')
		{
			$record[$key] = $value;	
		}
	}

	$record['phoneNumber'] = $phone;
	$record['ipAddress'] = $_SERVER['REMOTE_ADDR'];
	$record['dateSubmitted'] = date('Y-m-d H:i:s', time());
	
	$id = $db_drvr->Insert('RequirementsIncompleteForms',$record);
	
	if($id == 0)
		return false;

	return true;
}
//-------------------------------------------------------------------
function SendEmail($data, $date)
{
	$to = $data['emailAddress'];
	
	$subject = 'Graduation With Requirements Incomplete Form Submission';
	
	$headers = "From: stephen.armstrong@rochester.edu \r\n";
	$headers .= "CC: stephen.armstrong@rochester.edu \r\n";
	$headers .= "CC: kelly.johnson@rochester.edu \r\n";
    
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	
	$message = "<html><head><style>th, td{padding: 2px; text-align: left;} @media only screen and (max-width:480px){table{width:100% !important; max-width:480px !important;}</style></head><body>";
	$message .= "<table style='width:100%;'>";
	$message .= "<tr><td colspan='2' style='width: 100%; text-align: center; background-color: #021e47; color: #FFC125; border-bottom: 3px solid #FFC125; border-radius: 10px;'>Arts, Sciences and Engineering<hr/><div style='color:white; font-size: 175%; padding: 0px 0px 15px 0px;'><span style='color:#FFC125;'>U</span><span style='font-variant:small-caps;'>niversity</span> <i>of</i> <span style='color:#FFC125;'>R</span><span style='font-variant:small-caps;'>ochester</span></div></td></tr>";
	$message .= "<tr><td colspan='2'>You have submitted a Graduation With Requirements Incomplete Form:</td></tr><br/>";

	$message .= "<tr><td colspan='2'>Submission of this document DOES NOT guarantee your form has been accepted.</td></tr>";
	
	$message .= "<tr><td>Are you interested in learning more about the ninth and/or tenth-semester process?</td></tr>";

	if(!empty($data['learningYes']))
	{
		$message .= "<tr><td>Yes</td></tr>";
	}
	else if(!empty($data['learningNo']))
	{
		$message .= "<tr><td>No</td></tr>";
	}
	else if(!empty($data['spokenYes']))
	{
		$message .= "<tr><td>I have already spoken with my Financial Aid Counselor.</td></tr>";
	}

	$message .= "<tr><td colspan='2'><b>Student Information</b><hr/></td></tr>";

	$message .= "<tr><td>College you are completing the requirements for your degree:</td></tr>";

	if(!empty($data['artsYes']))
	{
		$message .= "<tr><td>College of Arts and Sciences</td></tr>";
	}
	if(!empty($data['hajimYes']))
	{
		$message .= "<tr><td>Hajim School of Engineering and Applied Sciences</td></tr>";
	}
	if(!empty($data['eastmanYes']))
	{
		$message .= "<tr><td>Dual Degree Eastman</td></tr>";
	}

	$message .= "<tr><td>&nbsp;&nbsp;</td></tr>";

	$message .= "<tr><td>Student UID: " . $data['studentID'] . "</td></tr>";
    $message .= "<tr><td>Student Name: " . $data['studentFirstName'] . " " . $data['studentMiddleInitial'] . " " . $data['studentLastName'] . "</td></tr>";
    $message .= "<tr><td>Permanent Address: " . $data['permAddress'] . "</td></tr>";
    $message .= "<tr><td>Permanent Phone #: " . $data['phoneNumber'] . "</td></tr>";
	$message .= "<tr><td>Email Address: " . $data['emailAddress'] . "</td></tr>";
	$message .= "<tr><td>Major(s): " . $data['major'] . "</td></tr>";
	$message .= "<tr><td>Minor(s): " . $data['minor'] . "</td></tr>";
	$message .= "<tr><td>Cluster(s): " . $data['cluster'] . "</td></tr>";
	$message .= "<tr><td>&nbsp;</td></tr>";

	$message .= "<tr><td colspan='2'><b>Rochester Curriculum Information</b><hr/></td></tr>";

	$message .= "<tr><td>Do you need to make a change to your Major(s), Minor(s), Cluster(s) based on what is listed?</td></tr>";
	if(!empty($data['changeYes']))
	{
		$message .= "<tr><td>Yes</td></tr>";

		$message .= "<tr><td colspan='2'><b>Division of Humanities</b></td></tr>";

		$message .= "<tr><td>Major: " . $data['humanitiesMajor1'] . "</td></tr>";
		$message .= "<tr><td>Minor: " . $data['humanitiesMinor1'] . "</td></tr>";
		$message .= "<tr><td>Cluster(s): " . $data['humanitiesCluster1'];
		if(!empty($data['humanitiesCluster2']))
		{
			$message .= ", " . $data['humanitiesCluster2'];
		}
		$message .= "</td></tr>";

		$message .= "<tr><td colspan='2'><b>Division of Social Sciences</b></td></tr>";
		$message .= "<tr><td>Major: " . $data['socialSciencesMajor1'] . "</td></tr>";
		$message .= "<tr><td>Minor: " . $data['socialSciencesMinor1'] . "</td></tr>";
		$message .= "<tr><td>Cluster(s): " . $data['socialSciencesCluster1'];
		if(!empty($data['socialSciencesCluster2']))
		{
			$message .= ", " . $data['socialSciencesCluster2'];
		}
		$message .= "</td></tr>";

		$message .= "<tr><td colspan='2'><b>Division of Natural Sciences</b></td></tr>";
		$message .= "<tr><td>Major: " . $data['naturalSciencesMajor1'] . "</td></tr>";
		$message .= "<tr><td>Minor: " . $data['naturalSciencesMinor1'] . "</td></tr>";
		$message .= "<tr><td>Cluster(s): " . $data['naturalSciencesCluster1'];
		if(!empty($data['naturalSciencesCluster2']))
		{
			$message .= ", " . $data['naturalSciencesCluster2'];
		}
	}
	else if(!empty($data['changeNo']))
	{
		$message .= "<tr><td>No</td></tr>";
	}


	$message .= "<br/><br/><tr><td>Summarize the circumstances relating to why you did not complete your degree:</td></tr>";
	$message .= "<tr><td>" . $data['circumstances'] . "</td></tr>";
	$message .= "<tr><td>&nbsp;</td></tr>";
	$message .= "<tr><td>Additional information, questions/concerns:</td></tr>";
	$message .= "<tr><td>" . $data['circumstancesPart2'] . "</td></tr>";
	$message .= "<tr><td>&nbsp;</td></tr>";
	
	$message .= "</td></tr></table><br/><br/>";
	
		$message .= "<table style='width:100%;'><tr>";
		$message .= "<td><b>Course Rationale</b></td>";
		$message .= "<td><b>Course Number</b></td>";
		$message .= "<td><b>Course Title</b></td>";
		$message .= "<td><b>Where Course Will Be Taken</b></td>";
		$message .= "<td><b>When Course Will Be Taken</b></td></tr>";

		if(!empty($data['courseRationale1']))
		{
			$message .= "<tr><td>";
			$message .= $data['courseRationale1'];
			$message .= "</td>";
		}
		else
		{
			$message .= "<tr><td>";
			$message .= "&nbsp;";
			$message .= "</td>";
		}

		if(!empty($data['courseNumber1']))
		{
			$message .= "<td>";
			$message .= $data['courseNumber1'];
			$message .= "</td>";
		}
		else
		{
			$message .= "<td>";
			$message .= "&nbsp;";
			$message .= "</td>";
		}

		if(!empty($data['courseTitle1']))
		{
			$message .= "<td>";
			$message .= $data['courseTitle1'];
			$message .= "</td>";
		}
			
		if(!empty($data['courseWhere1']))
		{
			$message .= "<td>";
			$message .= $data['courseWhere1'];
			$message .= "</td>";
		}

		if(!empty($data['courseStart1']))
		{
			$message .= "<td>";
			$message .= $data['courseStart1'];
			$message .= "</td></tr>";
		}

		if(!empty($data['courseRationale2']))
		{
			$message .= "<tr><td>";
			$message .= $data['courseRationale2'];
			$message .= "</td>";
		}
		else
		{
			$message .= "<tr><td>";
			$message .= "&nbsp;";
			$message .= "</td>";
		}

		if(!empty($data['courseNumber2']))
		{
			$message .= "<td>";
			$message .= $data['courseNumber2'];
			$message .= "</td>";
		}
		else
		{
			$message .= "<td>";
			$message .= "&nbsp;";
			$message .= "</td>";
		}

		if(!empty($data['courseTitle2']))
		{
			$message .= "<td>";
			$message .= $data['courseTitle2'];
			$message .= "</td>";
		}
		else
		{
			$message .= "<td>";
			$message .= "&nbsp;";
			$message .= "</td>";
		}
			
		if(!empty($data['courseWhere2']))
		{
			$message .= "<td>";
			$message .= $data['courseWhere2'];
			$message .= "</td>";
		}
		else
		{
			$message .= "<td>";
			$message .= "&nbsp;";
			$message .= "</td>";
		}
			
		if(!empty($data['courseStart2']))
		{
			$message .= "<td>";
			$message .= $data['courseStart2'];
			$message .= "</td></tr>";
		}
		else
		{
			$message .= "<td>";
			$message .= "&nbsp;";
			$message .= "</td></tr>";
		}

		if(!empty($data['courseRationale3']))
		{
			$message .= "<tr><td>";
			$message .= $data['courseRationale3'];
			$message .= "</td>";
		}
		else
		{
			$message .= "<tr><td>";
			$message .= "&nbsp;";
			$message .= "</td>";
		}

		if(!empty($data['courseNumber3']))
		{
			$message .= "<td>";
			$message .= $data['courseNumber3'];
			$message .= "</td>";
		}
		else
		{
			$message .= "<td>";
			$message .= "&nbsp;";
			$message .= "</td>";
		}

		if(!empty($data['courseTitle3']))
		{
			$message .= "<td>";
			$message .= $data['courseTitle3'];
			$message .= "</td>";
		}
		else
		{
			$message .= "<td>";
			$message .= "&nbsp;";
			$message .= "</td>";
		}
			
		if(!empty($data['courseWhere3']))
		{
			$message .= "<td>";
			$message .= $data['courseWhere3'];
			$message .= "</td>";
		}
		else
		{
			$message .= "<td>";
			$message .= "&nbsp;";
			$message .= "</td>";
		}
			
		if(!empty($data['courseStart3']))
		{
			$message .= "<td>";
			$message .= $data['courseStart3'];
			$message .= "</td></tr>";
		}
		else
		{
			$message .= "<td>";
			$message .= "&nbsp;";
			$message .= "</td></tr>";
		}

		if(!empty($data['courseRationale4']))
		{
			$message .= "<tr><td>";
			$message .= $data['courseRationale4'];
			$message .= "</td>";
		}
		else
		{
			$message .= "<tr><td>";
			$message .= "&nbsp;";
			$message .= "</td>";
		}

		if(!empty($data['courseNumber4']))
		{
			$message .= "<td>";
			$message .= $data['courseNumber4'];
			$message .= "</td>";
		}
		else
		{
			$message .= "<td>";
			$message .= "&nbsp;";
			$message .= "</td>";
		}

		if(!empty($data['courseTitle4']))
		{
			$message .= "<td>";
			$message .= $data['courseTitle4'];
			$message .= "</td>";
		}
		else
		{
			$message .= "<td>";
			$message .= "&nbsp;";
			$message .= "</td>";
		}
			
		if(!empty($data['courseWhere4']))
		{
			$message .= "<td>";
			$message .= $data['courseWhere4'];
			$message .= "</td>";
		}
		else
		{
			$message .= "<td>";
			$message .= "&nbsp;";
			$message .= "</td>";
		}
			
		if(!empty($data['courseStart4']))
		{
			$message .= "<td>";
			$message .= $data['courseStart4'];
			$message .= "</td></tr>";
		}
		else
		{
			$message .= "<td>";
			$message .= "&nbsp;";
			$message .= "</td></tr>";
		}

		if(!empty($data['courseRationale5']))
		{
			$message .= "<tr><td>";
			$message .= $data['courseRationale5'];
			$message .= "</td>";
		}
		else
		{
			$message .= "<tr><td>";
			$message .= "&nbsp;";
			$message .= "</td>";
		}

		if(!empty($data['courseNumber5']))
		{
			$message .= "<td>";
			$message .= $data['courseNumber5'];
			$message .= "</td>";
		}
		else
		{
			$message .= "<td>";
			$message .= "&nbsp;";
			$message .= "</td>";
		}

		if(!empty($data['courseTitle5']))
		{
			$message .= "<td>";
			$message .= $data['courseTitle5'];
			$message .= "</td>";
		}
		else
		{
			$message .= "<td>";
			$message .= "&nbsp;";
			$message .= "</td>";
		}
			
		if(!empty($data['courseWhere5']))
		{
			$message .= "<td>";
			$message .= $data['courseWhere5'];
			$message .= "</td>";
		}
		else
		{
			$message .= "<td>";
			$message .= "&nbsp;";
			$message .= "</td>";
		}
			
		if(!empty($data['courseStart5']))
		{
			$message .= "<td>";
			$message .= $data['courseStart5'];
			$message .= "</td></tr>";
		}
		else
		{
			$message .= "<td>";
			$message .= "&nbsp;";
			$message .= "</td></tr>";
		}

		if(!empty($data['courseRationale6']))
		{
			$message .= "<tr><td>";
			$message .= $data['courseRationale6'];
			$message .= "</td>";
		}
		else
		{
			$message .= "<tr><td>";
			$message .= "&nbsp;";
			$message .= "</td>";
		}

		if(!empty($data['courseNumber6']))
		{
			$message .= "<td>";
			$message .= $data['courseNumber6'];
			$message .= "</td>";
		}
		else
		{
			$message .= "<td>";
			$message .= "&nbsp;";
			$message .= "</td>";
		}

		if(!empty($data['courseTitle6']))
		{
			$message .= "<td>";
			$message .= $data['courseTitle6'];
			$message .= "</td>";
		}
		else
		{
			$message .= "<td>";
			$message .= "&nbsp;";
			$message .= "</td>";
		}
			
		if(!empty($data['courseWhere6']))
		{
			$message .= "<td>";
			$message .= $data['courseWhere6'];
			$message .= "</td>";
		}
		else
		{
			$message .= "<td>";
			$message .= "&nbsp;";
			$message .= "</td>";
		}
			
		if(!empty($data['courseStart6']))
		{
			$message .= "<td>";
			$message .= $data['courseStart6'];
			$message .= "</td></tr>";
		}
		else
		{
			$message .= "<td>";
			$message .= "&nbsp;";
			$message .= "</td></tr>";
		}
		
		if(!empty($data['courseRationale7']))
		{
			$message .= "<tr><td>";
			$message .= $data['courseRationale7'];
			$message .= "</td>";
		}
		else
		{
			$message .= "<tr><td>";
			$message .= "&nbsp;";
			$message .= "</td>";
		}

		if(!empty($data['courseNumber7']))
		{
			$message .= "<td>";
			$message .= $data['courseNumber7'];
			$message .= "</td>";
		}
		else
		{
			$message .= "<td>";
			$message .= "&nbsp;";
			$message .= "</td>";
		}

		if(!empty($data['courseTitle7']))
		{
			$message .= "<td>";
			$message .= $data['courseTitle7'];
			$message .= "</td>";
		}
		else
		{
			$message .= "<td>";
			$message .= "&nbsp;";
			$message .= "</td>";
		}
			
		if(!empty($data['courseWhere7']))
		{
			$message .= "<td>";
			$message .= $data['courseWhere7'];
			$message .= "</td>";
		}
		else
		{
			$message .= "<td>";
			$message .= "&nbsp;";
			$message .= "</td>";
		}
			
		if(!empty($data['courseStart7']))
		{
			$message .= "<td>";
			$message .= $data['courseStart7'];
			$message .= "</td></tr>";
		}
		else
		{
			$message .= "<td>";
			$message .= "&nbsp;";
			$message .= "</td></tr>";
		}
		
		if(!empty($data['courseRationale8']))
		{
			$message .= "<tr><td>";
			$message .= $data['courseRationale8'];
			$message .= "</td>";
		}
		else
		{
			$message .= "<tr><td>";
			$message .= "&nbsp;";
			$message .= "</td>";
		}

		if(!empty($data['courseNumber8']))
		{
			$message .= "<td>";
			$message .= $data['courseNumber8'];
			$message .= "</td>";
		}
		else
		{
			$message .= "<td>";
			$message .= "&nbsp;";
			$message .= "</td>";
		}

		if(!empty($data['courseTitle8']))
		{
			$message .= "<td>";
			$message .= $data['courseTitle8'];
			$message .= "</td>";
		}
		else
		{
			$message .= "<td>";
			$message .= "&nbsp;";
			$message .= "</td>";
		}
			
		if(!empty($data['courseWhere8']))
		{
			$message .= "<td>";
			$message .= $data['courseWhere8'];
			$message .= "</td>";
		}
		else
		{
			$message .= "<td>";
			$message .= "&nbsp;";
			$message .= "</td>";
		}
			
		if(!empty($data['courseStart8']))
		{
			$message .= "<td>";
			$message .= $data['courseStart8'];
			$message .= "</td></tr>";
		}
		else
		{
			$message .= "<td>";
			$message .= "&nbsp;";
			$message .= "</td></tr>";
		}
        
	$message .= "</table><br/>";
	
	$message .= "<table style='width:100%;'>";
    
	$message .= "<tr><td colspan='2'><b>Faculty Information</b><hr/></td></tr>";
	
	if(!empty($data['facultyYes']))
	{
		$message .= "<tr><td>You have indicated that you did speak to your department faculty advisor about your course plan.</td></tr>";
		$message .= "<tr><td>Faculty First Name: " . $data['facultyFirstName'] . "</td></tr>";
    	$message .= "<tr><td>Faculty Last Name: " . $data['facultyLastName'] . "</td></tr>";
	}
	else
	{
		$message .= "<tr><td>You have indicated that you did not speak to your department faculty advisor about your course plan.</td></tr>";
	}
	
	$message .= "<tr><td colspan='2'><hr/></td></tr>";
	$message .= "<tr><td>Date/Time Submitted: " . $date . "</td></tr>";
	$message .= "<tr><td colspan='2' style='width: 100%; text-align: center; background-color: #021e47; color: white; border-top: 3px solid #FFC125; border-radius: 10px;'><p>Copyright &#169; 2013&#150;2015. All rights reserved.<br /><a style='color:white;' href='http://www.rochester.edu/'>University of Rochester</a> | <a style='color:white;' href='http://www.rochester.edu/college/'>AS&#38;E</a> | <a style='color:white;' href='index.html'>Registrar</a><br/><a style='color:white;' href='http://www.rochester.edu/accessibility.html'>Accessibility</a> | <a style='color:white;' href='http://text.rochester.edu/tt/referrer' title='Access a text-only version of this page.'>Text</a> | <a style='color:white;' href='http://www.rochester.edu/college/webcomm/' title='Get help with your AS&amp;E website.'>Web Communications</a></p></td></tr>";
	$message .="</table>";	
	$message .= "</body></html>";
	
	mail($to, $subject, $message, $headers);
}
