<?php
//*******************************************************************************************************
//	requirements-incomplete.php  -- Submission of requirements-incomplete Forms.
//
//	Author: Linda Vasavong
//	Date Created: ????
//	Date Modified: Linda Vasavong
//*******************************************************************************************************

require_once('form_processors/m_requirements-incomplete.php');

// Setup the stock Responsive header and the page container
$html .= "<div class='page row'>";

if(!isset($_SESSION['LoggedIn']))
{
  if($status == "OK")
  {
		$html .= "<br/><div class='row--with-column-borders'><div class='columns small-12 medium-10 large-10 text-center alert_panel_succ medium-centered section--thick'>You have successfully submitted your Graduation with Requirements Incomplete Form! You will receive email confirmation of the submitted form.</div></div>";
  }
  $html .= $loginForm->GetRiverBankInputDisplay();
}
else
{
	ob_start();
	
	if($status == "DB_ERR")
		$html .= "<br/><div class='row--with-column-borders'><div class='columns small-12 medium-10 large-10 text-center alert_panel_succ medium-centered section--thick'>There was a problem submitting this form to the Database, the database could currently be offline. Contact the College Center for Advising Services (585) 275-2354 for further assistance.</div></div>";
	
	if(!$validTest && !empty($errors))
		$html .= "<br/><div class='row--with-column-borders'><div class='columns small-12 medium-10 large-10 text-center alert_panel_fail medium-centered section--thick'>One or more required fields indicated below have been left blank!</div></div>"; 
	
  if(!$validTest && !empty($error_messages))
  {
    echo $common->GetErrorDisplay($error_messages); 
  }
  
  ?>
<article class="columns small-12">
<br/>
<fieldset class="formField">
	<div class="row--with-borders">
        <div class="columns small-12">
            <h2>Graduation with Requirements Incomplete Form</h2>
        </div>
    </div>
    </br>
    </br>
    <div class="row--with-borders">
        <div class="columns small-12">
            <p align="center"><b>NOTE:</b> Fields marked with <span class="required">*</span> are <b>required</b> fields</p><br>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <p>
                If you are an Undergraduate Senior who will not complete all degree requirements by May but can reasonably expect to complete them by December 31st of this year, you may participate in the May Commencement ceremonies. Below list the courses you must complete to satisfy the requirements for your degree. <mark style="background-color:gold;">Submission of this form indicates that you understand we will not award your diploma until you complete your outstanding requirements.</mark> If all requirements below are not complete, you will have to request reclassification or readmission for your degree sometime after December 31st. Students who do not receive their degrees with their initial Class Year may choose to affiliate with that Class through Alumni Relations.
            </p>
        </div>
    </div>
    </br>
    <div class="row--with-borders">
        <div class="columns small-12">
        <p><b>For course(s) taken at another college:</b></p>
            <ul>
                <li>Complete a Course Approval Form (stating equivalencies and containing appropriate signatures). Information about taking courses at another school can be found <a href="https://www.rochester.edu/college/ccas/handbook/transfer-credit.html" target="_blank">here</a>.</li>
                <li>Send an official transcript to the College Center for Advising Services, Lattimore Hall showing a grade of C or better. Information about sending official transcripts can be found <a href="https://www.rochester.edu/college/ccas/handbook/transfer-credit.html" target="_blank">here</a>.</li>
                <li>Meet an academic advisor at the College Center for Advising Services (CCAS) in Lattmore 312 before taking the course(s). </li>
            </ul>
        </div>
    </div>
    <form action="?" method="POST">
    <div class="row--with-borders">
        <div class="columns small-12">
            <p><b>For course(s) taken at the University of Rochester</b></p>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <p <?php if(in_array('learning',$errors)) echo "class='error'";?>><span class="required">*</span>Are you interested in learning more about the ninth and/or tenth-semester process?</p>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <p><input type="checkbox" name="learningYes" id="learningYes" onclick="checkLearningYes()" size='3' value='Yes' <?php if($formData['learningYes'] == 'Yes') echo ' checked';?>/>&nbsp;&nbsp;Yes</p>
            <p><input type="checkbox" name="learningNo" id="learningNo" onclick="checkLearningNo()" size='3' value='Yes' <?php if($formData['learningNo'] == 'Yes') echo ' checked';?>/>&nbsp;&nbsp;No</p>
            <p><input type="checkbox" name="spokenYes" id="spokenYes" onclick="checkSpokenYes()" size='3' value='Yes' <?php if($formData['spokenYes'] == 'Yes') echo ' checked';?>/>&nbsp;&nbsp;I have already spoken with my Financial Aid Counselor.</p>
        </div>
    </div>
    </br>
    </br>
    <div class="row--with-borders">
        <div class="columns small-12">
            <p>
                <b>NOTE:</b> If you are making changes to a Major or Minor, you will need to meet with your Faculty Advisor <strong>before</strong> submitting the form.
            </p>
        </div>
    </div>
    <div class="row-with-borders">
        <div class="columns small-12">
            <p>
                <b>NOTE: Complete this form by Wednesday before Commencement, if not possible, please discuss your circumstances with an Advisor in the College Center for Advising Services in Lattimore 312.</b>
            </p>
        </div>
    </div>
    <br/>
    <br/>
    <div class="row--with-borders">
        <div class="columns small-12">
            <h3>Student Information</h3>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <p <?php if(in_array('school',$errors)) echo "class='error'";?>><span class="required">*</span>Please check which school you are completing the requirements for your degree.</p>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <p><input type="checkbox" name="artsYes" id="artsYes" size='3' value='Yes' <?php if($formData['artsYes'] == 'Yes') echo ' checked';?>/>&nbsp;&nbsp;School of Arts and Sciences</p>
            <p><input type="checkbox" name="hajimYes" id="hajimYes" size='3' value='Yes' <?php if($formData['hajimYes'] == 'Yes') echo ' checked';?>/>&nbsp;&nbsp;Hajim School of Engineering and Applied Sciences</p>
            <p><input type="checkbox" name="eastmanYes" id="eastmanYes" size='3' value='Yes' <?php if($formData['eastmanYes'] == 'Yes') echo ' checked';?>/>&nbsp;&nbsp;Dual Degree at Eastman</p>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12 medium-4 large-4">
            <label for="studentFirstName" <?php if(in_array('studentFirstName',$errors)) echo "class='error'";?>><span class="required">*</span>First Name</label>
            <input type="text" id="studentFirstName" name="studentFirstName" readonly value="<?php echo $userData['firstName'];?>"/>
        </div>
        <div class="columns small-12 medium-4 large-4">
            <label for="studentLastName" <?php if(in_array('studentLastName',$errors)) echo "class='error'";?>><span class="required">*</span>Last Name</label>
            <input type="text" id="studentLastName" name="studentLastName" readonly value="<?php echo $userData['lastName'];?>"/>
        </div>
        <div class="columns small-12 medium-1 large-1">
            <label for="studentMiddleInitial">M.I.</label>
            <input type="text" id="studentMiddleInitial" maxlength='1' name="studentMiddleInitial" value="<?php echo $formData['studentMiddleInitial'];?>"/>
        </div>
        <div class="columns small-12 medium-3 large-3">
            <label for="studentID" <?php if(in_array('studentID',$errors)) echo "class='error'";?>><span class="required">*</span>Student ID</label>
            <input type="text" id="studentID" name="studentID" maxlength='8' size='8' readonly value="<?php echo $userData['studentID'];?>"/>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12 medium-5 large-5">
            <label for="permAddress" <?php if(in_array('permAddress',$errors)) echo "class='error'";?>><span class="required">*</span>Permanent Address</label>
            <input type="text" id="permAddress" name="permAddress" value="<?php echo $formData['permAddress'];?>" maxlength="150">
        </div>
        <div class="columns small-12 medium-5 large-3">
            <label for="phoneNumber" <?php if(in_array('phoneNumber',$errors)) echo "class='error'";?>><span class="required">*</span>Permanent Phone #</label>
            <input type="text" id="phoneNumber" name="phoneNumber" maxlength="25" value="<?php echo $formData['phoneNumber'];?>"/>
        </div>
        <div class="columns small-12 medium-5 large-4">
            <label for="emailAddress" <?php if(in_array('emailAddress',$errors)) echo "class='error'";?>><span class="required">*</span>Email Address</label>
            <input type="text" id="emailAddress" name="emailAddress" maxlength="70" value="<?php echo $userData['emailAddress'];?>"/>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12 medium-5 large-4">
            <label for="major" <?php if(in_array('major',$errors)) echo "class='error'";?>><span class="required">*</span>Major(s)</label>
            <input type="text" name="major" readonly value="<?php echo $userData['majors'];?>"/>
        </div>
        <div class="columns small-12 medium-5 large-4">
            <label for="minor">Minor(s)</label>
            <input type="text" name="minor" readonly value="<?php echo $userData['minors'];?>"/>
        </div>
        <div class="columns small-12 medium-5 large-4">
            <label for="cluster" <?php if(in_array('cluster',$errors)) echo "class='error'";?>><span class="required">*</span>Cluster(s)</label>
            <input type="text" name="cluster" readonly value="<?php echo $userData['clusters'];?>"/>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <h3>Rochester Curriculum Information</h3>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <p <?php if(in_array('change',$errors)) echo "class='error'";?>>
                <span class="required">*</span>Do you need to make a change to your Major(s), Minor(s), Cluster(s) based on what is listed above?
                &nbsp;&nbsp;&nbsp;Yes&nbsp;<input type="checkbox" name="changeYes" id="changeYes" onclick="checkChangeYes()" size='3' value='Yes' <?php if($formData['changeYes'] == 'Yes') echo ' checked';?>/>
                &nbsp;&nbsp;&nbsp;No&nbsp;<input type="checkbox" name="changeNo" id="changeNo" onclick="checkChangeNo()" size='3' value='Yes' <?php if($formData['changeNo'] == 'Yes') echo ' checked';?>/>
            </p>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <p>
                <strong>Note: </strong>If you need to declare your major/minor, please fill out this form <a href="https://secure1.rochester.edu/registrar/applications/major-minor-declaration.php" target="_blank">here</a>. 
            </p>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <p>
                <strong>Note: </strong>If you need to drop major(s)/minor(s), please fill out this form <a href="https://secure1.rochester.edu/ccas/rc-change-form.php" target="_blank">here</a>.
            </p>
        </div>
    </div>
    <div id="divisions">
        <div class="row--with-borders">
            <div class="columns small-12">
                <h4 align="center">Division of Humanities</h4>
            </div>
        </div>
        <div class="row--with-borders">
            <div class="columns small-12 medium-3 large-6">
                <label for="humanitiesMajor1">Major</label>
                <select id="humanitiesMajor1" name="humanitiesMajor1"><?php echo $common->GetMajorOptions("H",$formData['humanitiesMajor1']); ?></select>
            </div>
            <div class="columns small-12 medium-3 large-6">
                <label for="humanitiesMinor1">Minor</label>
                <select id="humanitiesMinor1" name="humanitiesMinor1"><?php echo $common->GetMinorOptions("H",$formData['humanitiesMinor1']); ?></select>
            </div>
        </div>
        <div class="row--with-borders">
            <div class="columns small-12 medium-3 large-6">
                <label for="humanitiesCluster1">Cluster 1</label>
                <select id="humanitiesCluster1" name="humanitiesCluster1"><?php echo $common->GetClusterOptions("HUM",$formData['humanitiesCluster1']); ?></select>
            </div>
            <div class="columns small-12 medium-3 large-6">
                <label for="humanitiesCluster2">Cluster 2</label>
                <select id="humanitiesCluster2" name="humanitiesCluster2"><?php echo $common->GetClusterOptions("HUM",$formData['humanitiesCluster2']); ?></select>
            </div>
        </div>
        <div class="row--with-borders">
            <div class="columns small-12">
                <h4 align="center">Division of Social Sciences</h4>
            </div>
        </div>
        <div class="row--with-borders">
            <div class="columns small-12 medium-3 large-6">
                <label for="socialSciencesMajor1">Major</label>
                <select id="socialSciencesMajor1" name="socialSciencesMajor1"><?php echo $common->GetMajorOptions("S",$formData['socialSciencesMajor1']); ?></select>
            </div>
            <div class="columns small-12 medium-3 large-6">
                <label for="socialSciencesMinor1">Minor</label>
                <select id="socialSciencesMinor1" name="socialSciencesMinor1"><?php echo $common->GetMinorOptions("S",$formData['socialSciencesMinor1']); ?></select>
            </div>
        </div>
        <div class="row--with-borders">
            <div class="columns small-12 medium-3 large-6">
                <label for="socialSciencesCluster1">Cluster 1</label>
                <select id="socialSciencesCluster1" name="socialSciencesCluster1"><?php echo $common->GetClusterOptions("SSC",$formData['socialSciencesCluster1']); ?></select>
            </div>
            <div class="columns small-12 medium-3 large-6">
                <label for="socialSciencesCluster2">Cluster 2</label>
                <select id="socialSciencesCluster2" name="socialSciencesCluster2"><?php echo $common->GetClusterOptions("SSC",$formData['socialSciencesCluster2']); ?></select>
            </div>
        </div>
        <div class="row--with-borders">
            <div class="columns small-12">
                <h4 align="center">Division of Natural Sciences, Mathematics, and Engineering</h4>
            </div>
        </div>
        <div class="row--with-borders">
            <div class="columns small-12 medium-3 large-6">
                <label for="naturalSciencesMajor1">Major</label>
                <select id="naturalSciencesMajor1" name="naturalSciencesMajor1"><?php echo $common->GetMajorOptions("N",$formData['naturalSciencesMajor1']); ?></select>
            </div>
            <div class="columns small-12 medium-3 large-6">
                <label for="naturalSciencesMinor1">Minor</label>
                <select id="naturalSciencesMinor1" name="naturalSciencesMinor1"><?php echo $common->GetMinorOptions("N",$formData['naturalSciencesMinor1']); ?></select>
            </div>
        </div>
        <div class="row--with-borders">
            <div class="columns small-12 medium-3 large-6">
                <label for="naturalSciencesCluster1">Cluster 1</label>
                <select id="naturalSciencesCluster1" name="naturalSciencesCluster1"><?php echo $common->GetClusterOptions("NSE",$formData['naturalSciencesCluster1']); ?></select>
            </div>
            <div class="columns small-12 medium-3 large-6">
                <label for="naturalSciencesCluster2">Cluster 2</label>
                <select id="naturalSciencesCluster2" name="naturalSciencesCluster2"><?php echo $common->GetClusterOptions("NSE",$formData['naturalSciencesCluster2']); ?></select>
            </div>
        </div>
    </div>
    <div class="row--with--borders">
        <div class="columns small-12">
            <label for="circumstances" <?php if(in_array('circumstances',$errors)) echo "class='error'";?>><span class="required">*</span>Summarize the circumstances relating to why you did not complete your degree.</label>
            <p align="center"><textarea name="circumstances" rows="5" cols="70" id="circumstances" onkeydown="textCounter(this.form.circumstances,this.form.circumstancesCount,240);" onkeyup="textCounter(this.form.circumstances,this.form.circumstancesCount,240);"><?php echo $formData['circumstances']?></textarea></p>
        </div>
    </div>
    <div class="row--with--borders">
        <div class="columns small-3 medium-1 large-1">
            <input class="text-center" readonly type="text" id="circumstancesCount" name="circumstancesCount" value="240">
        </div>
        <div class="columns small-8 medium-3 end">
            <label for="circumstancesRemaining" class="postfix radius">Characters Remaining</label>
        </div>
    </div>


    <div class="row--with--borders">
        <div class="columns small-12">
            <label for="circumstancesPart2">Additional information, questions/concerns.</label>
            <p align="center"><textarea name="circumstancesPart2" rows="5" cols="70" id="circumstancesPart2" onkeydown="textCounter(this.form.circumstancesPart2,this.form.circumstancesPart2Count,240);" onkeyup="textCounter(this.form.circumstancesPart2,this.form.circumstancesPart2Count,240);"><?php echo $formData['circumstancesPart2']?></textarea></p>
        </div>
    </div>
    <div class="row--with--borders">
        <div class="columns small-3 medium-1 large-1">
            <input class="text-center" readonly type="text" id="circumstancesPart2Count" name="circumstancesPart2Count" value="240">
        </div>
        <div class="columns small-8 medium-3 end">
            <label for="circumstancesPart2Remaining" class="postfix radius">Characters Remaining</label>
        </div>
    </div>

	<div class="row--with-borders">
        <div class="columns small-12">
            <p <?php if(in_array('courseRow',$errors)) echo "class='error'"; else if(in_array('courseRow1',$errors)) echo "class='error'";?>><span class="required">*</span>Please use this section to indicate the course(s) that will be used to complete your degree. </p>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <table align="center">
                <tbody><tr><th>Course Rationale</th><th>Course <br>Number</th><th>Course Title</th><th>Where Course Will Be Taken</th><th>When Course Will <br>Be Taken</th></tr>
                    <tr><td>e.g., Major</td><td>CSC 172</td><td>Data Structures and Algorithms</td><td>University of Rochester</td><td>Spring 2020</td></tr>
                    <tr><td><select style="width: 100%;" name="courseRationale1"><option value="" <?php echo ($formData['courseRationale1'] == "" ? " selected" : "");?>>--Select--</option><option value="Major" <?php echo ($formData['courseRationale1'] == "Major" ? " selected" : "");?>>Major</option><option value="Minor" <?php echo ($formData['courseRationale1'] == "Minor" ? " selected" : "");?>>Minor</option><option value="Cluster" <?php echo ($formData['courseRationale1'] == "Cluster" ? " selected" : "");?>>Cluster</option><option value="Elective Credit" <?php echo ($formData['courseRationale1'] == "Elective Credit" ? " selected" : "");?>>Elective Credit</option></select></td><td><input type="text" id='courseNumber1' name="courseNumber1" size='12' maxlength='10' value="<?php echo $formData['courseNumber1'];?>"/></td><td><input type="text" maxlength='70' size='35' name="courseTitle1" id='courseTitle1' class='title1' value="<?php echo $formData['courseTitle1'];?>"/></td><td><input type="text" size='35' maxlength='70' name="courseWhere1" id='courseWhere1' value="<?php echo $formData['courseWhere1'];?>"/></td><td><select name="courseStart1" id="courseStart1"><?php echo $common->GetSemesterOptions(0,10,$formData['courseStart1']);?></select></td></tr>
                    <tr id="courseRow2"><td><select style="width: 100%;" id="courseRationale2" name="courseRationale2"><option value="" <?php echo ($formData['courseRationale2'] == "" ? " selected" : "");?>>--Select--</option><option value="Major" <?php echo ($formData['courseRationale2'] == "Major" ? " selected" : "");?>>Major</option><option value="Minor" <?php echo ($formData['courseRationale2'] == "Minor" ? " selected" : "");?>>Minor</option><option value="Cluster" <?php echo ($formData['courseRationale2'] == "Cluster" ? " selected" : "");?>>Cluster</option><option value="Elective Credit" <?php echo ($formData['courseRationale2'] == "Elective Credit" ? " selected" : "");?>>Elective Credit</option></select></td><td><input type="text" id='courseNumber2' name="courseNumber2" size='12' maxlength='10' value="<?php echo $formData['courseNumber2'];?>"/></td><td><input type="text" maxlength='70' size='35' name="courseTitle2" id='courseTitle2' class='title2' value="<?php echo $formData['courseTitle2'];?>"/></td><td><input type="text" size='35' maxlength='70' name="courseWhere2" id='courseWhere2' value="<?php echo $formData['courseWhere2'];?>"/></td><td><select name="courseStart2" id="courseStart2"><?php echo $common->GetSemesterOptions(0,10,$formData['courseStart2']);?></select></td></tr>
                    <tr id="courseRow3"><td><select style="width: 100%;" id="courseRationale3" name="courseRationale3"><option value="" <?php echo ($formData['courseRationale3'] == "" ? " selected" : "");?>>--Select--</option><option value="Major" <?php echo ($formData['courseRationale3'] == "Major" ? " selected" : "");?>>Major</option><option value="Minor" <?php echo ($formData['courseRationale3'] == "Minor" ? " selected" : "");?>>Minor</option><option value="Cluster" <?php echo ($formData['courseRationale3'] == "Cluster" ? " selected" : "");?>>Cluster</option><option value="Elective Credit" <?php echo ($formData['courseRationale3'] == "Elective Credit" ? " selected" : "");?>>Elective Credit</option></select></td><td><input type="text" id='courseNumber3' name="courseNumber3" size='12' maxlength='10' value="<?php echo $formData['courseNumber3'];?>"/></td><td><input type="text" maxlength='70' size='35' name="courseTitle3" id='courseTitle3' class='title3' value="<?php echo $formData['courseTitle3'];?>"/></td><td><input type="text" size='35' maxlength='70' name="courseWhere3" id='courseWhere3' value="<?php echo $formData['courseWhere3'];?>"/></td><td><select name="courseStart3" id="courseStart3"><?php echo $common->GetSemesterOptions(0,10,$formData['courseStart3']);?></select></td></tr>
                    <tr id="courseRow4"><td><select style="width: 100%;" id="courseRationale4" name="courseRationale4"><option value="" <?php echo ($formData['courseRationale4'] == "" ? " selected" : "");?>>--Select--</option><option value="Major" <?php echo ($formData['courseRationale4'] == "Major" ? " selected" : "");?>>Major</option><option value="Minor" <?php echo ($formData['courseRationale4'] == "Minor" ? " selected" : "");?>>Minor</option><option value="Cluster" <?php echo ($formData['courseRationale4'] == "Cluster" ? " selected" : "");?>>Cluster</option><option value="Elective Credit" <?php echo ($formData['courseRationale4'] == "Elective Credit" ? " selected" : "");?>>Elective Credit</option></select></td><td><input type="text" id='courseNumber4' name="courseNumber4" size='12' maxlength='10' value="<?php echo $formData['courseNumber4'];?>"/></td><td><input type="text" maxlength='70' size='35' name="courseTitle4" id='courseTitle4' class='title4' value="<?php echo $formData['courseTitle4'];?>"/></td><td><input type="text" size='35' maxlength='70' name="courseWhere4" id='courseWhere4' value="<?php echo $formData['courseWhere4'];?>"/></td><td><select name="courseStart4" id="courseStart4"><?php echo $common->GetSemesterOptions(0,10,$formData['courseStart4']);?></select></td></tr>
                    <tr id="courseRow5"><td><select style="width: 100%;" id="courseRationale5" name="courseRationale5"><option value="" <?php echo ($formData['courseRationale5'] == "" ? " selected" : "");?>>--Select--</option><option value="Major" <?php echo ($formData['courseRationale5'] == "Major" ? " selected" : "");?>>Major</option><option value="Minor" <?php echo ($formData['courseRationale5'] == "Minor" ? " selected" : "");?>>Minor</option><option value="Cluster" <?php echo ($formData['courseRationale5'] == "Cluster" ? " selected" : "");?>>Cluster</option><option value="Elective Credit" <?php echo ($formData['courseRationale5'] == "Elective Credit" ? " selected" : "");?>>Elective Credit</option></select></td><td><input type="text" id='courseNumber5' name="courseNumber5" size='12' maxlength='10' value="<?php echo $formData['courseNumber5'];?>"/></td><td><input type="text" maxlength='70' size='35' name="courseTitle5" id='courseTitle5' class='title5' value="<?php echo $formData['courseTitle5'];?>"/></td><td><input type="text" size='35' maxlength='70' name="courseWhere5" id='courseWhere5' value="<?php echo $formData['courseWhere5'];?>"/></td><td><select name="courseStart5" id="courseStart5"><?php echo $common->GetSemesterOptions(0,10,$formData['courseStart5']);?></select></td></tr>
                    <tr id="courseRow6"><td><select style="width: 100%;" id="courseRationale6" name="courseRationale6"><option value="" <?php echo ($formData['courseRationale6'] == "" ? " selected" : "");?>>--Select--</option><option value="Major" <?php echo ($formData['courseRationale6'] == "Major" ? " selected" : "");?>>Major</option><option value="Minor" <?php echo ($formData['courseRationale6'] == "Minor" ? " selected" : "");?>>Minor</option><option value="Cluster" <?php echo ($formData['courseRationale6'] == "Cluster" ? " selected" : "");?>>Cluster</option><option value="Elective Credit" <?php echo ($formData['courseRationale6'] == "Elective Credit" ? " selected" : "");?>>Elective Credit</option></select></td><td><input type="text" id='courseNumber6' name="courseNumber6" size='12' maxlength='10' value="<?php echo $formData['courseNumber6'];?>"/></td><td><input type="text" maxlength='70' size='35' name="courseTitle6" id='courseTitle6' class='title6' value="<?php echo $formData['courseTitle6'];?>"/></td><td><input type="text" size='35' maxlength='70' name="courseWhere6" id='courseWhere6' value="<?php echo $formData['courseWhere6'];?>"/></td><td><select name="courseStart6" id="courseStart6"><?php echo $common->GetSemesterOptions(0,10,$formData['courseStart6']);?></select></td></tr>
                    <tr id="courseRow7"><td><select style="width: 100%;" id="courseRationale7" name="courseRationale7"><option value="" <?php echo ($formData['courseRationale7'] == "" ? " selected" : "");?>>--Select--</option><option value="Major" <?php echo ($formData['courseRationale7'] == "Major" ? " selected" : "");?>>Major</option><option value="Minor" <?php echo ($formData['courseRationale7'] == "Minor" ? " selected" : "");?>>Minor</option><option value="Cluster" <?php echo ($formData['courseRationale7'] == "Cluster" ? " selected" : "");?>>Cluster</option><option value="Elective Credit" <?php echo ($formData['courseRationale7'] == "Elective Credit" ? " selected" : "");?>>Elective Credit</option></select></td><td><input type="text" id='courseNumber7' name="courseNumber7" size='12' maxlength='10' value="<?php echo $formData['courseNumber7'];?>"/></td><td><input type="text" maxlength='70' size='35' name="courseTitle7" id='courseTitle7' class='title7' value="<?php echo $formData['courseTitle7'];?>"/></td><td><input type="text" size='35' maxlength='70' name="courseWhere7" id='courseWhere7' value="<?php echo $formData['courseWhere7'];?>"/></td><td><select name="courseStart7" id="courseStart7"><?php echo $common->GetSemesterOptions(0,10,$formData['courseStart7']);?></select></td></tr>
                    <tr id="courseRow8"><td><select style="width: 100%;" id="courseRationale8" name="courseRationale8"><option value="" <?php echo ($formData['courseRationale8'] == "" ? " selected" : "");?>>--Select--</option><option value="Major" <?php echo ($formData['courseRationale8'] == "Major" ? " selected" : "");?>>Major</option><option value="Minor" <?php echo ($formData['courseRationale8'] == "Minor" ? " selected" : "");?>>Minor</option><option value="Cluster" <?php echo ($formData['courseRationale8'] == "Cluster" ? " selected" : "");?>>Cluster</option><option value="Elective Credit" <?php echo ($formData['courseRationale8'] == "Elective Credit" ? " selected" : "");?>>Elective Credit</option></select></td><td><input type="text" id='courseNumber8' name="courseNumber8" size='12' maxlength='10' value="<?php echo $formData['courseNumber8'];?>"/></td><td><input type="text" maxlength='70' size='35' name="courseTitle8" id='courseTitle8' class='title8' value="<?php echo $formData['courseTitle8'];?>"/></td><td><input type="text" size='35' maxlength='70' name="courseWhere8" id='courseWhere8' value="<?php echo $formData['courseWhere8'];?>"/></td><td><select name="courseStart8" id="courseStart8"><?php echo $common->GetSemesterOptions(0,10,$formData['courseStart8']);?></select></td></tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <h3>Department or Faculty Advisor Information<h3>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <p <?php if(in_array('facultyYes',$errors)) echo "class='error'";?>><span class="required">*</span>
                Did you speak to your department or faculty advisor about your course plan?
                &nbsp;&nbsp;&nbsp;Yes&nbsp;<input type="checkbox" name="facultyYes" id="facultyYes" onclick="checkFacultyYes()" size='3' value='Yes' <?php if($formData['facultyYes'] == 'Yes') echo ' checked';?>/>
                &nbsp;&nbsp;&nbsp;No&nbsp;<input type="checkbox" name="facultyNo" id="facultyNo" onclick="checkFacultyNo()" size='3' value='Yes' <?php if($formData['facultyNo'] == 'Yes') echo ' checked';?>/>
            </p>
        </div>
    </div>
    <div class="row--with-borders" id="facultyName">
        <div class="columns small-12">
            <div style="display:flex;align-items:center;justify-content:center;">
                <div class="columns small-12 small-1 medium-4">
                    <label for="facultyFirstName" <?php if(in_array("facultyFirstName",$errors)) echo "class='error'";?>>Department or Faculty Advisor's<br>First Name</label>
                    <input type="text" name="facultyFirstName" id="facultyFirstName" maxlength="100" value="<?php echo $formData['facultyFirstName']?>"/>
                </div>
                <div class="columns small-12 medium-4 large-4">
                    <label for="facultyLastName" <?php if(in_array("facultyLastName",$errors)) echo "class='error'";?>>Department or Faculty Advisor's<br>Last Name</label>
                    <input type="text" name="facultyLastName" id="facultyLastName" maxlength="100" value="<?php echo $formData['facultyLastName']?>"/>
                </div>
            </div>
        </div>
    </div>
    </br><br>
    <div class="row--with-borders">
        <div class="text-center columns small-12">
            <input class="small button secondary button-pop" name="Save" type="submit" value="Submit"/>
        </div>
    </div>
</form>
</fieldset>
<br/>
</article>

<script>

function textCounter(field, countField, maxLimit) 
{
    if(field.value.length > maxLimit)
    {
        field.value = field.value.substring(0,maxLimit)
    }
    else
    {
        countField.value = maxLimit - field.value.length;
    }
}

function checkFacultyYes()
{
    if(document.getElementById("facultyYes").checked == true)
    {
        document.getElementById("facultyNo").checked = false;
    }
}

function checkFacultyNo()
{
    if(document.getElementById("facultyNo").checked == true)
    {
        document.getElementById("facultyYes").checked = false;
    }
}

function checkChangeYes()
{
    if(document.getElementById("changeYes").checked == true)
    {
        document.getElementById("changeNo").checked = false;
    }
}

function checkChangeNo()
{
    if(document.getElementById("changeNo").checked == true)
    {
        document.getElementById("changeYes").checked = false;
    }
}

function checkLearningYes()
{
    if(document.getElementById("learningYes").checked == true)
    {
        document.getElementById("learningNo").checked = false;
        document.getElementById("spokenYes").checked = false;
    }
}

function checkLearningNo()
{
    if(document.getElementById("learningNo").checked == true)
    {
        document.getElementById("learningYes").checked = false;
        document.getElementById("spokenYes").checked = false;
    }
}

function checkSpokenYes()
{
    if(document.getElementById("spokenYes").checked == true)
    {
        document.getElementById("learningYes").checked = false;
        document.getElementById("learningNo").checked = false;
    }
}

$(document).ready(function () {

    var cntY = 0;
    var cntN = 0;

    $("#divisions").hide();

    $("#facultyName").hide();

    if(document.getElementById('facultyYes').checked == true) 
    {
        $("#facultyName").show();
    }

    document.getElementById('facultyYes').addEventListener('change', function () {
        if(this.checked) {
            $("#facultyName").show();
        }
        else {
            $("#facultyName").hide();

            if(document.getElementById("facultyYes").checked == true) {
                document.getElementById("facultyYes").checked = false;
            }
            else {
                document.getElementById("facultyNo").checked = true;
            }
            $('#facultyFirstName').val('');
            $('#facultyLastName').val('');
        }
    });

    document.getElementById('facultyNo').addEventListener('change', function () {
        if(this.checked) {
            $("#facultyName").hide();

            if(document.getElementById("facultyYes").checked == true) {
                document.getElementById("facultyYes").checked = false;
            }
            else {
                document.getElementById("facultyNo").checked = true;
            }
            $('#facultyFirstName').val('');
            $('#facultyLastName').val('');
        }
    });

    if(document.getElementById('changeYes').checked == true) 
    {
        $("#divisions").show();
    }

    document.getElementById('changeYes').addEventListener('change', function () {
        if(this.checked) {
            $("#divisions").show();
        }
        else {
            $("#divisions").hide();

            if(document.getElementById("changeYes").checked == true) {
                document.getElementById("changeYes").checked = false;
            }
            else {
                document.getElementById("changeNo").checked = true;
            }
            $('#humanitiesMajor1').val('');
            $('#humanitiesMinor1').val('');
            $('#humanitiesCluster1').val('');
            $('#humanitiesCluster2').val('');

            $('#socialSciencesMajor1').val('');
            $('#socialSciencesMinor1').val('');
            $('#socialSciencesCluster1').val('');
            $('#socialSciencesCluster2').val('');

            $('#naturalSciencesMajor1').val('');
            $('#naturalSciencesMinor1').val('');
            $('#naturalSciencesCluster1').val('');
            $('#naturalSciencesCluster2').val('');
        }
    });

    document.getElementById('changeNo').addEventListener('change', function () {
        if(this.checked) {
            $("#divisions").hide();

            if(document.getElementById("changeYes").checked == true) {
                document.getElementById("changeYes").checked = false;
            }
            else {
                document.getElementById("changeNo").checked = true;
            }
            $('#humanitiesMajor1').val('');
            $('#humanitiesMinor1').val('');
            $('#humanitiesCluster1').val('');
            $('#humanitiesCluster2').val('');

            $('#socialSciencesMajor1').val('');
            $('#socialSciencesMinor1').val('');
            $('#socialSciencesCluster1').val('');
            $('#socialSciencesCluster2').val('');

            $('#naturalSciencesMajor1').val('');
            $('#naturalSciencesMinor1').val('');
            $('#naturalSciencesCluster1').val('');
            $('#naturalSciencesCluster2').val('');
        }
    });

    document.getElementById('humanitiesMajor1').addEventListener('change', function () {
        var major = document.getElementById('humanitiesMajor1');
        var selectedValue = major.options[major.selectedIndex].value;
        var str1 = "You have indicated to change your major/minor. \n\nPlease go to this link and fill out the form if you want to declare a major/minor:\n";
        var str2 = String("https://secure1.rochester.edu/registrar/applications/major-minor-declaration.php \nPlease go to this link and fill out the form if you want to add or drop major(s)/minor(s):\nhttps://secure1.rochester.edu/ccas/rc-change-form.php");
        var result = str1.concat(str2);
        if(selectedValue != "") {
            alert(result);
        }
    });

    document.getElementById('humanitiesMinor1').addEventListener('change', function () {
        var minor = document.getElementById('humanitiesMinor1');
        var selectedValue = minor.options[minor.selectedIndex].value;
        var str1 = "You have indicated to change your major/minor. \n\nPlease go to this link and fill out the form if you want to declare a major/minor:\n";
        var str2 = String("https://secure1.rochester.edu/registrar/applications/major-minor-declaration.php \nPlease go to this link and fill out the form if you want to add or drop major(s)/minor(s):\nhttps://secure1.rochester.edu/ccas/rc-change-form.php");
        var result = str1.concat(str2);
        if(selectedValue != "") {
            alert(result);
        }
    });

    document.getElementById('humanitiesCluster1').addEventListener('change', function () {
        var cluster = document.getElementById('humanitiesCluster1');
        var selectedValue = cluster.options[cluster.selectedIndex].value;
        var str1 = "You have indicated to change your clusters. \n\nPlease go to this link and fill out the form: \n";
        var str2 = String("https://secure1.rochester.edu/ccas/rc-change-form.php");
        var result = str1.concat(str2);
        if(selectedValue != "") {
            alert(result);
        }
    });

    document.getElementById('humanitiesCluster2').addEventListener('change', function () {
        var cluster = document.getElementById('humanitiesCluster2');
        var selectedValue = cluster.options[cluster.selectedIndex].value;
        var str1 = "You have indicated to change your clusters. \n\nPlease go to this link and fill out the form: \n";
        var str2 = String("https://secure1.rochester.edu/ccas/rc-change-form.php");
        var result = str1.concat(str2);
        if(selectedValue != "") {
            alert(result);
        }
    });

    document.getElementById('socialSciencesMajor1').addEventListener('change', function () {
        var major = document.getElementById('socialSciencesMajor1');
        var selectedValue = major.options[major.selectedIndex].value;
        var str1 = "You have indicated to change your major/minor. \n\nPlease go to this link and fill out the form if you want to declare a major/minor:\n";
        var str2 = String("https://secure1.rochester.edu/registrar/applications/major-minor-declaration.php \nPlease go to this link and fill out the form if you want to add or drop major(s)/minor(s):\nhttps://secure1.rochester.edu/ccas/rc-change-form.php");
        var result = str1.concat(str2);
        if(selectedValue != "") {
            alert(result);
        }
    });

    document.getElementById('socialSciencesMinor1').addEventListener('change', function () {
        var minor = document.getElementById('socialSciencesMinor1');
        var selectedValue = minor.options[minor.selectedIndex].value;
        var str1 = "You have indicated to change your major/minor. \n\nPlease go to this link and fill out the form if you want to declare a major/minor:\n";
        var str2 = String("https://secure1.rochester.edu/registrar/applications/major-minor-declaration.php \nPlease go to this link and fill out the form if you want to add or drop major(s)/minor(s):\nhttps://secure1.rochester.edu/ccas/rc-change-form.php");
        var result = str1.concat(str2);
        if(selectedValue != "") {
            alert(result);
        }
    });

    document.getElementById('socialSciencesCluster1').addEventListener('change', function () {
        var cluster = document.getElementById('socialSciencesCluster1');
        var selectedValue = cluster.options[cluster.selectedIndex].value;
        var str1 = "You have indicated to change your clusters. \n\nPlease go to this link and fill out the form: \n";
        var str2 = String("https://secure1.rochester.edu/ccas/rc-change-form.php");
        var result = str1.concat(str2);
        if(selectedValue != "") {
            alert(result);
        }
    });

    document.getElementById('socialSciencesCluster2').addEventListener('change', function () {
        var cluster = document.getElementById('socialSciencesCluster2');
        var selectedValue = cluster.options[cluster.selectedIndex].value;
        var str1 = "You have indicated to change your clusters. \n\nPlease go to this link and fill out the form: \n";
        var str2 = String("https://secure1.rochester.edu/ccas/rc-change-form.php");
        var result = str1.concat(str2);
        if(selectedValue != "") {
            alert(result);
        }
    });

    document.getElementById('naturalSciencesMajor1').addEventListener('change', function () {
        var major = document.getElementById('naturalSciencesMajor1');
        var selectedValue = major.options[major.selectedIndex].value;
        var str1 = "You have indicated to change your major/minor. \n\nPlease go to this link and fill out the form if you want to declare a major/minor:\n";
        var str2 = String("https://secure1.rochester.edu/registrar/applications/major-minor-declaration.php \nPlease go to this link and fill out the form if you want to add or drop major(s)/minor(s):\nhttps://secure1.rochester.edu/ccas/rc-change-form.php");
        var result = str1.concat(str2);
        if(selectedValue != "") {
            alert(result);
        }
    });

    document.getElementById('naturalSciencesMinor1').addEventListener('change', function () {
        var minor = document.getElementById('naturalSciencesMinor1');
        var selectedValue = minor.options[minor.selectedIndex].value;
        var str1 = "You have indicated to change your major/minor. \n\nPlease go to this link and fill out the form if you want to declare a major/minor:\n";
        var str2 = String("https://secure1.rochester.edu/registrar/applications/major-minor-declaration.php \nPlease go to this link and fill out the form if you want to add or drop major(s)/minor(s):\nhttps://secure1.rochester.edu/ccas/rc-change-form.php");
        var result = str1.concat(str2);
        if(selectedValue != "") {
            alert(result);
        }
    });

    document.getElementById('naturalSciencesCluster1').addEventListener('change', function () {
        var cluster = document.getElementById('naturalSciencesCluster1');
        var selectedValue = cluster.options[cluster.selectedIndex].value;
        var str1 = "You have indicated to change your clusters. \n\nPlease go to this link and fill out the form: \n";
        var str2 = String("https://secure1.rochester.edu/ccas/rc-change-form.php");
        var result = str1.concat(str2);
        if(selectedValue != "") {
            alert(result);
        }
    });

    document.getElementById('naturalSciencesCluster2').addEventListener('change', function () {
        var cluster = document.getElementById('naturalSciencesCluster2');
        var selectedValue = cluster.options[cluster.selectedIndex].value;
        var str1 = "You have indicated to change your clusters. \n\nPlease go to this link and fill out the form: \n";
        var str2 = String("https://secure1.rochester.edu/ccas/rc-change-form.php");
        var result = str1.concat(str2);
        if(selectedValue != "") {
            alert(result);
        }
    });

});

</script>
<?php		
	$html .= ob_get_contents();
	ob_end_clean();
}

$html .= "</div>";	//Make sure we close the page container.

$style = "style_riverbank.css";
$pageTitle = "Graduation With Requirements Incomplete Form";
$pageHeader = "Graduation With Requirements Incomplete Form";
$pageContent = $html;


include_once('templates/responsive_riverbank.php');
?>