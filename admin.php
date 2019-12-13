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
<?php include("adminCookie.php") ?>

<!-- navigation -->
<div class="container-fluid" id="roundtab" role="navigation">
    <ul class="nav nav-pills">
        <li class="active"><a data-toggle="tab" id="assign" href="#assignment">Bug Assignment</a></li>
        <li><a data-toggle="tab" id="assign2" href="#userUpdate">Update Bug Assignment</a></li>
        <li><a data-toggle="tab" id="editor" href="#edit">Bug Creation and Editing</a></li>
    </ul>

<!-- content -->
<div class="tab-content clearfix">

<!-- assignment content -->
<div class="tab-pane active" id="assignment" >
    <div class="area" id="assign">
        <div class="left">
            <label for="user_input">User List</label><br/>
            <input type="text" id="user_input" list="user-list" placeholder="Type a user name"><br />
            <datalist id="user-list" aria-labelledby="user-list">
            <?php
                $db = new Database();
                $users = $db->generateUserList();
                foreach($users as $user){
                    echo "<option id=\"u_$user[0]\" value=\"$user[1]\" />";
                }
            ?>
            </datalist><p>
            <div>
            <label for="start">Start Date</label> <input type="date" id="start" value="<?php date_default_timezone_set('America/New_York'); echo date('Y-m-d'); ?>"><p>
            <label for="end">End Date</label> <input type="date" id="end"><p>
            <textarea name="review" id="review" rows="10" cols="50" hidden></textarea>
            </div>
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
            </select><p>
            <input type="button" id="bug_assignment" class="btn btn-primary" onclick="reviewAssignment()" value="Review Bug Assignment">
        </div>
    </div>
</div>

<!-- bug editing content -->
<div class="tab-pane" id="edit">
    <div class="area" id="editor">
        <div class="left">
            <label for="bugs">Bug List</label> <br />
            <input type="text" id="bug-input" list="bug-list" onchange="updateBugForm()" placeholder="Enter a bug name">
            <datalist id="bug-list" aria-labelledby="bug-list">
            <?php
                $db = new Database();
                $bugs = $db->generateBugList();
                foreach($bugs as $bug){
                    echo "<option id=\"be_$bug[0]\" value=\"$bug[0] - $bug[1]\" title=\"$bug[2]\" />";
                }
            ?>
            </datalist>
        </div>
        <div class="right">
        <label for="bug_name">Bug Name</label><br />
        <input type="text" id="bug_name"> <br />
        <input type="hidden" id="bug_id" value="">
        <label for="bug_functional_area">Functional Area</label><br />
        <input type="text" id="bug_functional_area" title="Enter page or section where the bug will be active."><br />
        <label for="bug_description">Description</label><br />
        <input type="text" id="bug_description"><br />
        <label for="bug_code">Codeblock</label><br />
        <textarea id="bug_code" name="bug_code" rows="10" cols="50"></textarea><br />
        <input type="button" class="btn btn-primary" onclick="updateBug()" value="Update Bug">
        <input type="button" class="btn btn-warning" onclick="saveNewBug()" value="Save New Bug">
        </div>
    </div>
</div>
<!-- update bug assignments content -->
<div class="tab-pane" id="userUpdate">
    <div class="area" id="assign2">
        <div class="full">
            <!-- Define list of users -->
            <label for="users_update">User List</label> <br />
            <input type="text" id="users_update" list="update-list" onchange="populateBugList()" placeholder="Enter a user name"><br />
            <datalist id="update-list" aria-labelledby="update-list">
            <?php
                $db = new Database();
                $users = $db->generateUserList();
                foreach($users as $user){
                    echo "<option id=\"uu_$user[0]\" value=\"$user[1]\" />";
                }
            ?>
            </datalist><br />

            <!-- Define list of bugs assigned to selected user -->
            <table id="user-bug-list" style="display:none" >
                <thead><tr><th>Bug Name</th><th>Start Date</th><th>End Date</th><th>Actions</th></tr></thead>
                <tbody></tbody>
            </table>
    </div>
</div>
</div>
<script>
// bug assignment functions
function updateDate(){
    document.getElementById("start").value = new Date().toDateInputValue();
}

function reviewAssignment(){
    var newline = "\r\n";
    var doc = document.getElementById("user_input");
    var username = doc.value;
    var userid = $("#user-list option[value='" + username + "']").attr('id').replace("u_","");
    console.log(userid);
    var doc2 = document.getElementById("bugs");
    var buglist = username + newline;
    for(var opt of doc2.options){
        if(opt.selected){
            buglist += opt.value + "," + opt.text + newline;
        }
    }
    document.getElementById("review").innerHTML = buglist;
    document.getElementById("review").removeAttribute("hidden");
    document.getElementById("bug_assignment").setAttribute("onclick", "saveAssignment()");
    document.getElementById("bug_assignment").value = "Save Bug Assignment";
}

function saveAssignment(){
    var doc = document.getElementById("user_input");
    var username = doc.value;
    var uid = $("#user-list option[value='" + username + "']").attr('id').replace("u_","");
    var doc2 = document.getElementById("bugs");
    var buglist = new Array();
    // add selected bugs to an array
    for(var opt of doc2.options){
        if(opt.selected){
            $bid = opt.id;
            buglist.push($bid.replace("b_",""));     
        }
    }

    var sdate = start.value == "" ? new Date().toJSON().slice(0,10) : start.value;
    var edate = end.value;

    // Clear form elements.
    resetAssignmentForm();

    // call ajax function for each separate item in the bug array
    if(edate == ""){
        for(let i = 0; i < buglist.length; i++){
            var bid = buglist[i];
            $.ajax({
                url:"saveAssignment.php",
                method:"POST",
                data:{ bugid: bid, userid: uid, sdate: sdate },
                success:function(data){ alert(data); }
            });
        }
    }else{
        for(let j = 0; j < buglist.length; j++){
            $.ajax({
                url:"saveAssignment.php",
                method:"POST",
                data:{ bugid: buglist[j], userid: uid, sdate: sdate, edate: edate },
                success:function(data){ alert(data); }
            });
        }
    }
}

// clear assignment form data
function resetAssignmentForm(){
    document.getElementById("review").innerHTML = "";
    document.getElementById("review").hidden = true;
    document.getElementById("user_input").val = "";
    document.getElementById("start").val = "";
    document.getElementById("end").val = "";
    document.getElementById("bug_assignment").setAttribute("onclick", "reviewAssignment()");
    document.getElementById("bug_assignment").value = "Review Bug Assignment";

    var doc2 = document.getElementById("bugs");
    for(var opt of doc2.options){
        if(opt.selected){
            opt.removeAttribute("selected");
        }
    }
}

// bug editing functions
// clear bug form data
function resetBugForm(){
    document.getElementById("bug_name").value = "";
    document.getElementById("bug_functional_area").value = "";
    document.getElementById("bug_description").value = "";
    document.getElementById("bug_code").value = "";
}

function saveNewBug(){
    var bugname = document.getElementById("bug_name").value;
    var funcarea = document.getElementById("bug_functional_area").value;
    var description = document.getElementById("bug_description").value;
    var codeblock = document.getElementById("bug_code").value;

    // Clear form elements.
    resetBugForm();

    $.ajax({
        url:"saveBug.php",
        method:"POST",
        data:{ name: bugname, functional_area: funcarea, description: description, codeblock: codeblock },
            success:function(response){ 
                var saved_bug = JSON.parse(response);
                alert("Bug " + saved_bug[0][0] + " " + saved_bug[0][1] + " successfully saved."); 
            }
    });
    //window.location.reload(true);
}

function updateBugForm(){
    // retrieve selected id by via name lookup
    var doc = document.getElementById("bug-input");
    var bugname = doc.value;
    var bugid = $("#bug-list option[value='" + bugname + "']").attr('id').replace("be_","");

    // send ajax request and split returned data into array and assign to form elements
    $.ajax({
        url:"getBugData.php",
        method:"POST",
        data:{ id: bugid },
        success:function(response){
            var temp_array = JSON.parse(response);
            if(temp_array.length == 1){
                document.getElementById("bug_id").value = temp_array[0][0];
                document.getElementById("bug_name").value = temp_array[0][1];
                document.getElementById("bug_functional_area").value = temp_array[0][2];
                document.getElementById("bug_description").value = temp_array[0][3];
                document.getElementById("bug_code").value = temp_array[0][4];
            }
        }
    });
}

function updateBug(){
    var id = document.getElementById("bug_id").value;
    var name = document.getElementById("bug_name").value;
    var area = document.getElementById("bug_functional_area").value;
    var desc = document.getElementById("bug_description").value;
    var code = document.getElementById("bug_code").value;
    // send ajax request and split returned data into array and assign to form elements
    $.ajax({
        url:"updateBugData.php",
        method:"POST",
        data:{ id: id, name: name, functional_area: area, description: desc, codeblock: code },
        success:function(response){ 
            alert(response);
        }
    });
}

function populateBugList(){
    var username = document.getElementById("users_update").value;
    var userid = $("#update-list option[value='" + username + "']").attr('id').replace("uu_","");
    // send ajax request and process returned data as an array for the bug assignment table
    $.ajax({
        url: "getUserBugAssignments.php",
        method: "POST",
        data: {userid: userid},
        success: function(response){
            var temp_array = JSON.parse(response);
            if(temp_array.length < 1){
                document.getElementById("user-bug-list").style.display = "none"; 
                return;
            }

            var table = document.getElementById("user-bug-list").getElementsByTagName("tbody")[0];
            document.getElementById("user-bug-list").style.removeProperty("display"); 
            var new_tbody = document.createElement("tbody");
            for(let i = 0; i < temp_array.length; i++){
                var newrow = new_tbody.insertRow(-1);
                newrow.setAttribute("id", "assignment_" + temp_array[i][0])
                var bname_cell = newrow.insertCell(0);
                var bstart_cell = newrow.insertCell(1);
                var bend_cell = newrow.insertCell(2);
                var bug_actions = newrow.insertCell(3);
                bname_cell.innerHTML = temp_array[i][1];
                bstart_cell.innerHTML = '<input type="date" id="sd_assign' + temp_array[i][0] + '" value="' + temp_array[i][2] + '" >';
                bend_cell.innerHTML = '<input type="date" id="ed_assign' + temp_array[i][0] + '" value="' + temp_array[i][3] + '" >';
                bug_actions.innerHTML = '<input type="button" class="btn btn-info" onclick="updateAssignment(' + temp_array[i][0] + ')" value="Update"> '; 
                bug_actions.innerHTML += '<input type="button" class="btn btn-danger" onclick="deleteAssignment(' + temp_array[i][0] + ')" value="Delete">';
            }
            table.parentNode.replaceChild(new_tbody,table);
        }
    });
}

function updateAssignment(id){
    var sdate = document.getElementById('sd_assign' + id).value == "" ? new Date().toJSON() : document.getElementById('sd_assign' + id).value;
    var edate = document.getElementById('ed_assign' + id).value;
    console.log(sdate + " - " + edate);
    // send ajax request and split returned data into array and assign to form elements
    $.ajax({
        url:"updateAssignment.php",
        method:"POST",
        data:{ id: id, sdate: sdate, edate: edate },
        success:function(response){ 
            alert(response);
        }
    });
}

function deleteAssignment(id){
    // send ajax request and split returned data into array and assign to form elements
    $.ajax({
        url:"deleteAssignment.php",
        method:"POST",
        data:{ id: id },
        success:function(response){ 
            alert("Assignment deletion was successful.");
        }
    });
    window.location.reload(true);
}
</script>

</body>
</html>