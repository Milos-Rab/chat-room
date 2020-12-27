<?php
    session_start();
    include 'mysql.php';
    include 'mongodb.php';

    $page = "index/admin_panel";

    if(empty($_SESSION)){        
        ob_start();
        header("Location: ./admin.php");
        ob_flush();
        die("");
    }
    
    extract($_SESSION);
    if($user_role!="admin"){
        die("forbidden");
    }

    if(isset($admin_confirm) && $admin_confirm!="confirm"){
        ob_start();
        header("Location: ./admin.php");
        ob_flush();
        die("");
    }
    $CHECK_TIME = 1.5;
    include './template/header.php';
?>

<div class="row">
          <!-- left site menu -->
          <div class="col-md-3">
            <div class="nav flex-column accordion-list-group" id="v-pills-tab" role="tablist" aria-orientation="vertical">
              <a class="nav-link active show" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="false">Rooms</a>
              <a class="nav-link" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="true">Users</a>
              <a class="nav-link" id="v-pills-messages-tab" data-toggle="pill" href="#v-pills-messages" role="tab" aria-controls="v-pills-messages" aria-selected="false">Messages</a>
              <a class="nav-link" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-settings" role="tab" aria-controls="v-pills-settings" aria-selected="false">IP Address</a>
            </div>
          </div>
          <div class="col-md-9">
            <div class="tab-content" id="v-pills-tabContent">
              <div class="tab-pane fade active show" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                <h2>Room List</h2>
                <div class="m-content">
                    <div><button class="btn btn-sm btn-primary" type="button" data-toggle="modal" data-target="#roomEditModal" data-roomname="" data-roomid="">Add New</button></div>
                    <table class="tablet table-striped " id="room_table">
                        <thead>
                            <th>Room Id</th>
                            <th>Room Name</th>
                            <th>Users Count</th>
                            <th>Action</th>
                        </thead>
                        <tbody>
<?php
    $res = $mysql_db->query("SELECT rooms.id, rooms.`room_name`, users.name , COUNT(NAME) AS cnt FROM rooms LEFT JOIN users ON users.`chat_room`=rooms.id GROUP BY rooms.id;");
    while($row=$res->fetch_assoc()){
?>
        <tr id="<?php echo $row['id'];?>">
            <td><?php echo $row['id']; ?></td>
            <td>
                <?php echo $row['room_name'];?>
            </td>
            <td>
                <?php echo $row['cnt'];?>
            </td>
            <td>
                <button class="btn btn-sm btn-success" id="edit" type="button" data-toggle="modal" data-target="#roomEditModal" data-roomname="<?php echo $row['room_name'] ?>" data-roomid="<?php echo $row['id']; ?>">Edit</button>
                <button class="btn btn-sm btn-danger" id="delete" type="button">Delete</button>
            </td>
        </tr>
<?php
    }
?>
                        </tbody>
                    </table>
                </div>
              </div>
              <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                <h2>User List</h2>
                <div class="m-content">
                    <table class="tablet table-striped " id="user_table">
                        <thead>
                            <th>No.</th>
                            <th>Name</th>
                            <th>Gender</th>
                            <th>Age</th>
                            <th>IP Address</th>
                            <th>Active</th>
                        </thead>
                        <tbody>
<?php
    $users_res = $mysql_db->query("SELECT users.`name`, users.age, users.`gender`, users.`ip_address`, users.`check_timeout`, users.`user_id` FROM users ORDER BY users.`check_timeout` DESC;");
    $num=1;
    $user = [];
    while ($row=$users_res->fetch_assoc()){
        $user[]=$row;
        $user_state="";
        if(time()-$row['check_timeout']<=$CHECK_TIME){
            $user_state="logged-in";
        }
?>
        <tr class="<?php echo $user_state; ?>" id="<?php echo $row['user_id'];?>">
            <td><?php echo $num;?></td>
            <td><?php echo $row['name'];?></td>
            <td><?php echo $row['gender'];?></td>
            <td><?php echo $row['age'];?></td>
            <td><?php echo $row['ip_address'];?></td>
            <td><div class="state" style="width:20px;height:20px;border-radius:50%;"></div></td>
        </tr>
<?php
    $num++;
    }
?>
                        </tbody>
                    </table>
                </div>
              </div>
              <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">
                <h2>Message List</h2>
                <div class="loader-container" style="height:calc(100vh - 80px);"><div class="loader"></div></div>
                
                <div class="m-content message-table-pan loading">
                    <div style="display:flex;justify-content:space-between;">
                        
                        <label>From: <select id="message_from">
                                <?php func_userlist($user);?>
                            </select>
                        </label>
                        <label>To: <select id="message_to">
                                <?php func_userlist($user);?>
                            </select>
                        </label>
                        <input type="date" id="start-date" value="2020-01-01">~<input type="date" id="end-date">
<?php
    function func_userlist($user){
?>
        <option value="">All</option>
<?php
        foreach($user as $u){
?>
            <option value="<?php echo $u['name']."(".$u['gender'].",".$u['age'].")"?>"><?php echo $u['name']."(".$u['gender'].",".$u['age'].")"?></option>
<?php            
        }
    }                    
?>                        
                    </div>
                    <table class="tablet table-striped " id="message_table">
                        <thead>
                            <th><input id="check-all" type="checkbox"></th>
                            <th>From</th>
                            <th>To</th>
                            <th>Content</th>
                            <th>Date Time</th>
                        </thead>
                    </table>
                    <div>
                        <button class="btn btn-sm btn-danger" id="delete-message" type="button">Delete</button>
                    </div>
                </div>                    
            
              </div>
              <div class="tab-pane fade" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                <h2>IP Address List</h2>
                <div class="m-content">
                    <table class="tablet table-striped " id="ip_table">
                        <thead>
                            <th>IP Address</th>
                            <th>Users Count</th>
                            <th>State</th>
                        </thead>
                        <tbody>
<?php
    $res = $mysql_db->query("SELECT users.ip_address, COUNT(*) AS user_cnt, blocked_users.`note`, blocked_users.`blocked` FROM users LEFT JOIN blocked_users ON users.`ip_address`=blocked_users.`ip_address` GROUP BY users.`ip_address`;");
    while($row=$res->fetch_assoc()){
?>
                        <tr>
                            <td><?php echo $row['ip_address']?></td>
                            <td><?php echo $row['user_cnt'];?></td>
                            <td>
                                <select>
                                    <option value="opened">Opened</option>
                                    <option value="blocked" <?php if($row['blocked']=='block') echo "selected"?>>Blocked</option>
                                </select>
                            </td>
                        </tr>
<?php
    }
?>                            
                        </tbody>
                    </table>
                </div>
              </div>
            </div>
          </div>
        </div>

<!-- Modals -->
<div class="modal fade" id="roomEditModal" tabindex="-1" role="dialog" aria-labelledby="roomEditModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="roomEditModalLabel">New message</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
            <input type="text" id="room-id" class="hidden" />
            <div class="form-group">
                <label for="room-name" class="col-form-label">Room Name:</label>
                <input type="text" class="form-control" id="room-name">
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" id="save" class="btn btn-primary">Save</button>
        <button type="button" id="close" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<?php
    include './template/footer.php';
?>
