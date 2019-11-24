<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bug Administration</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
    <link rel="stylesheet" href="adminstyles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</head>
<body>
<?php include("database.php") ?>

<!-- navigation -->
<div class="container" id="roundtab" role="navigation">
    <ul class="nav nav-pills">
        <li class="active"><a data-toggle="tab" id="assign" href="#assignment">Bug Assignment</a></li>
        <li><a data-toggle="tab" id="editor" href="#edit">Bug Creation and Editing</a></li>
    </ul>

<!-- content -->
<div class="tab-content clearfix">

<!-- assignment content -->
<div class="tab-pane active" id="assignment">
    <div class="listbox-area" id="assign">
        <div class="left">
            <label for="users">User List</label><br/>
            <input type="text" id="user_input" list="user-list" placeholder="Type a user name"><br />
            <datalist id="user-list" role="listbox" aria-labelledby="user-list">
            <?php
                $db = new Database();
                $users = $db->generateUserList();
                foreach($users as $user){
                    echo "<option id=\"u_$user[0]\" value=\"$user[0]\">$user[1]</option>";
                }
            ?>
            </datalist>
            <textarea name="review" id="review" rows="10" cols="50" hidden></textarea>
        </div>
        <div class="right">
            <label for="bugs">Bug List</label> <br/>
            <select id="bugs" class="multiselect-container" multiple role="listbox" aria-labelledby="bug-list">
            <?php
                $db = new Database();
                $bugs = $db->generateBugList();
                foreach($bugs as $bug){
                    echo "<option id=\"b_$bug[0]\" value=\"$bug[0]\" title=\"$bug[2]\" >$bug[1]</option>";
                }
            ?>
            </select><br />
            <input type="button" id="bug_assignment" class="btn btn-primary" onclick="reviewAssignment()" value="Review Bug Assignment">
        </div>
    </div>
</div>

<!-- bug editing content -->
<div class="tab-pane" id="edit">
    <div class="listbox-area" id="editor">
        <div class="left">
            <label for="bugs">Bug List</label> <br />
            <input type="text" id="bugs" list="bug-list" onchange="updateBugForm()" placeholder="Enter a bug name">
            <datalist id="bug-list" role="listbox" aria-labelledby="bug-list">
            <?php
                $db = new Database();
                $bugs = $db->generateBugList();
                foreach($bugs as $bug){
                    echo "<option id=\"be_$bug[0]\" title=\"$bug[2]\" >$bug[1]</option>";
                }
            ?>
            </datalist>
        </div>
        <div class="right">
        <label for="bug_name">Bug Name</label><br />
        <input type="text" id="bug_name"> <br />
        <label for="bug_functional_area">Functional Area</label><br />
        <input type="text" id="bug_functional_area"><br />
        <label for="bug_description">Description</label><br />
        <input type="text" id="bug_description"><br />
        <label for="bug_code">Codeblock</label><br />
        <textarea id="bug_code" name="bug_code" rows="10" cols="50"></textarea><br />
        <input type="button" class="btn btn-primary" onclick="updateBug()" value="Update Bug">
        <input type="button" class="btn btn-warning" onclick="saveNewBug()" value="Save New Bug">
    </div>
</div>
</div>
<script>
function reviewAssignment(){
    var newline = "\r\n";
    var doc = document.getElementById("user_input");
    var userid = doc.value;
    var username = document.getElementById("u_"+userid).innerHTML;
    var doc2 = document.getElementById("bugs");
    var buglist = username + newline;
    for(var opt of doc2.options){
        if(opt.selected){
            buglist += opt.value + "," + opt.text + newline;
        }
    }
    document.getElementById("review").innerHTML = buglist;
    document.getElementById("review").removeAttribute("hidden");
    document.getElementById("bug_assignment").onclick = "saveAssignment()";
    document.getElementById("bug_assignment").value = "Save Bug Assignment";
}
</script>
</body>
</html>