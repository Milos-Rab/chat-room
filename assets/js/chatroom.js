(function ($) {
    const CHECK_TIME = 1000;
    var intervals = 0;
    const ROOMMATE_ITEM = $('<div class="roommate-list-item">'
                        + '<div class="state"></div>'
                        + '<div class="content">'
                            + '<div class="name  d-flex justify-content-between">'
                                + '<span class="name"></span>'
                                + '<span class="dismiss">x</span>'
                            + '</div>'
                            + '<div class="last-message d-flex justify-content-between">'
                                + '<span class="last-message-content">...</span>'
                                + '<span class="badge badge-primary badge-pill new-message-count"></span>'
                            + '</div>'
                        + '</div>'
                    + '</div>')

    const CHAT_ITEM = $('<div class="chat-item">'
                            + '<div class="name-time">'
                                + '<span class="name"></span>, <span class="date-time"></span>'
                            + '</div>'
                            + '<div class="message"></div>'
                        + '</div>');

    const USER_LIST_ITEM = $('<li class="list-group-item d-flex justify-content-between">'
                            + '<span class="name-ag"></span>'
                            + '<span class="badge badge-primary badge-pill state">0</span>'
                        + '</li>');
    
    const user_list = [];
    const roommate_list = [];
    
    function chatScrollBottom(){
        scroll_height = $(".chat-list")[0].scrollHeight - $('.chat-list').height();
        $('.chat-list').animate({ scrollTop: scroll_height }, 200);
    }

    $(".tabs .toggle-show").click(function(){
        $(".tabs").toggleClass("show");
        $(".users").removeClass("show");
    })

    $(".users .toggle-show").click(function(){
        $(".users").toggleClass("show");
        $(".tabs").removeClass("show");
    })

    $("form.message-sender").submit(function(event){
        event.preventDefault();
        message = $(this).find("input").val();
        message = message.trim();
        $(this).find("input").val("");
        if($(".roommate-list-item.active").attr("id") && message){
            id = $(".roommate-list-item.active").attr("id");
            active_roommate = id.split("-")[1];
            active_name = $(".roommate-list-item.active").find("span.name").text();
            
            $.ajax({
                url: "./process.php",
                type: "post",
                data: {type: "ADD_NEW_MESSAGE", active_roommate: active_roommate, message: message},
                dataType: "json",
                success: function(res){
                    messages = res[0];
                    time = res[1];
                    addNewMessages(messages, active_roommate, active_name);
                    
                    var mi = CHAT_ITEM.clone();
                    mi.addClass("chat-item your-message").find(".name").text("You");                
                    var dateString = formatDateTime(time*1000);
                    mi.find(".date-time").text(dateString);
                    mi.find(".message").text(message);
                    mi.appendTo(".chat-list");
                    chatScrollBottom();
                }
            })
        }
    });

    // add roommate
    $(".users .room-user-list").on("click", ".list-group-item",function(){

        $(this).find(".state").text("0");
        id=$(this).attr("id").split("-");
        roommate_id = id[1];
        logged = $(this).hasClass("logged-in");
        roommate = roommate_list.filter(item=>item.roommate===roommate_id);

        if(roommate.length===0){
            roommate_list.push({roommate:roommate_id});
            $.ajax({
                url: "./process.php",
                type: "post",
                data: {type:"ADD_ROOMMATE", roommate_id:roommate_id},
                success: function(res){
                    if(res=="success"){
                        let roommate_item = ROOMMATE_ITEM.clone();

                        let roommate = user_list.find(user=>user.user_id==roommate_id);
                        $(".roommate-list-item").removeClass("active");
                        roommate_item.addClass("active").attr("id", "roommate-"+roommate_id).find("span.name").text(roommate.name);
                        
                        if(roommate.user_role=="admin"){
                            roommate_item.addClass("admin");
                        }
                        if(roommate.gender=="Male"){
                            roommate_item.addClass("male");
                        }else if(roommate.gender=="Female"){
                            roommate_item.addClass("female");
                        }
                        if(logged){
                            roommate_item.addClass("logged-in");
                        }else{
                            roommate_item.addClass("logged-out");
                        }
                        roommate_item.find("span.name").text(roommate.name);
                        roommate_item.appendTo(".roommate-list");
                        $("#user-"+roommate_id).addClass("active");
                        getActiveRoommateMessages();
                    }
                }
            })
        }
    });

    // active roommate or dismiss roommate
    $(".roommate-list").on("click", ".roommate-list-item", function(event){
        target_class = event.target.classList[0];
        
        if(target_class=="dismiss"){
            id = $(this).attr("id");
            is_active=$(this).hasClass("active");
            dismiss_roommate = id.split("-")[1];
            $.ajax({
                url: "./process.php",
                type: "POST",
                data: {type: "DISMISS_ROOMMATE", roommate: dismiss_roommate},
                success: function(res){
                    if(res=="success"){
                        $("#roommate-"+dismiss_roommate).remove();
                        $("#user-"+dismiss_roommate).removeClass("active");
                        if(is_active) {
                            $(".chat-list").empty();
                        }
                        new_roommate = roommate_list.filter(item=>item.roommate!=dismiss_roommate);
                        Object.assign(roommate_list, [], [...new_roommate]);
                        roommate_list.pop();
                    }
                }
            })
        }else{
            $(this).siblings().removeClass("active");
            $(this).addClass("active");
            getActiveRoommateMessages();
        }
    })

    function formatDateTime(mills){
        return new Date(mills*1000).toString().substring(0, 25);
    }
    function addNewMessages(messages, active_roommate, active_name){

        messages.map(item=>{
            let chat_item=CHAT_ITEM.clone();
            if(item.from==active_roommate){
                chat_item.addClass("other-user");
                chat_item.find(".name").text(active_name);
            }else{
                chat_item.addClass("your-message");
                chat_item.find(".name").text("You");
            }
            
            var dateString = formatDateTime(item.time*1000);

            chat_item.find(".date-time").text(dateString);
            chat_item.find(".message").text(item.content);
            chat_item.appendTo(".chat-list");
        })
    }

    function getActiveRoommateMessages(){
        clearInterval(intervals);
        $(".chat-list").empty();
        $(".chat .loader-container").removeClass("hidden");
        id = $(".roommate-list-item.active").attr("id");
        active_roommate = id.split("-")[1];
        active_name = $(".roommate-list-item.active").find("span.name").text();
        $.ajax({
            url: "./process.php",
            type: "POST",
            data: {type: "GET_ACTIVE_MESSAGES", active_roommate: active_roommate},
            dataType: "json",
            success: function(res){
                $("#roommate-"+active_roommate).find(".new-message-count").text("");
                $("#user-"+active_roommate).find(".state").text("0");
                $(".chat .loader-container").addClass("hidden");
                addNewMessages(res, active_roommate, active_name);
                chatScrollBottom();
                intervals = setInterval(getUpdateData, CHECK_TIME);
            }
        })
    }

    getRoomUsers();

    function getRoomUsers(){
        $.ajax({
            url: "./process.php",
            type:"POST",
            data: {type:"GET_USER_DATA"},
            dataType:"json",
            success: function(res){
                console.log(res);
                Object.assign(user_list, res[0]);
                Object.assign(roommate_list, res[1]);
                time = res[2];
                user_list.map(item=>{
                    user_item = USER_LIST_ITEM.clone();
                    user_item.attr("id", "user-"+item.user_id);

                    if(item.user_role=="admin"){
                        user_item.addClass("admin");
                    }
                    if(item.gender=="Male"){
                        user_item.addClass("male");
                    }else if(item.gender=="Female"){
                        user_item.addClass("female");
                    }
                    if(time-item.check_timeout<CHECK_TIME/1000*1.5){
                        user_item.addClass("logged-in");
                    }else{
                        user_item.addClass("logged-out");
                    }
                    user_item.find(".name-ag").text(item.name+" ("+item.age+", "+item.gender+")");
                    user_item.appendTo(".room-user-list");
                });

                roommate_list.map(item=>{
                    roommate_item = ROOMMATE_ITEM.clone();
                    
                    let roommate = user_list.find(user=>user.user_id==item.roommate);
                    roommate_item.attr("id", "roommate-"+roommate.user_id);
                    if(roommate.user_role=="admin"){
                        roommate_item.addClass("admin");
                    }
                    if(roommate.gender=="Male"){
                        roommate_item.addClass("male");
                    }else if(roommate.gender=="Female"){
                        roommate_item.addClass("female");
                    }
                    if(time-roommate.check_timeout<CHECK_TIME/1000*1.5){
                        roommate_item.addClass("logged-in");
                    }else{
                        roommate_item.addClass("logged-out");
                    }
                    roommate_item.find("span.name").text(roommate.name);
                    roommate_item.appendTo(".roommate-list");
                });
                $(".roommate-list .loader-container").addClass("hidden");
                $(".room-user-list .loader-container").addClass("hidden");
                intervals = setInterval(getUpdateData, CHECK_TIME);
            }

        })
    }
    getUpdateData();
    function getUpdateData(){
        active_roommate='';
        id = $(".roommate-list-item.active").attr("id");
        if(id){
            active_roommate = id.split("-")[1];
            active_name = $(".roommate-list-item.active").find("span.name").text();
        }
        console.log("update data");
        $.ajax({
            url:'./process.php',
            type: "POST",
            data: {type:"GET_UPDATE_DATA", check_timeout:CHECK_TIME/1000*1.9, active_roommate: active_roommate},
            dataType: "json",
            success: function(res){
                // console.log(res);
                const update_user = res[0];
                const new_roommate = res[1];
                const new_messages = res[2];
                update_user.map(uuser=>{
                    if(uuser.crt<CHECK_TIME/1000*1.9){
                        is_new_user = user_list.filter(item=>item.user_id==uuser.user_id);
                        if(is_new_user.length==0){
                            user_list.push(uuser);
                            user_item = USER_LIST_ITEM.clone();
                            user_item.attr("id", "user-"+uuser.user_id);
        
                            if(uuser.user_role=="admin"){
                                uuser.addClass("admin");
                            }
                            if(uuser.gender=="Male"){
                                user_item.addClass("male");
                            }else if(uuser.gender=="Female"){
                                user_item.addClass("female");
                            }
                            user_item.addClass("logged-in");
                            user_item.find(".name-ag").text(uuser.name+" ("+uuser.age+", "+uuser.gender+")");
                            user_item.appendTo(".room-user-list");
                        }
                    }
                    if(uuser.cht<CHECK_TIME/1000*1.9){
                        $("#user-"+uuser.user_id).removeClass("logged-out").addClass("logged-in");
                        $("#roommate-"+uuser.user_id).removeClass("logged-out").addClass("logged-in");
                    }else{
                        $("#user-"+uuser.user_id).removeClass("logged-in").addClass("logged-out");
                        $("#roommate-"+uuser.user_id).removeClass("logged-in").addClass("logged-out");
                    }
                });
                new_roommate.map(rmmate=>{
                    is_new_roommate = roommate_list.filter(item=>item.roommate==rmmate.roommate);
                    if(is_new_roommate.length==0){
                        roommate_list.push(rmmate);
                        roommate_item = ROOMMATE_ITEM.clone();
                        
                        let roommate = user_list.find(user=>user.user_id==rmmate.roommate);

                        roommate_item.attr("id", "roommate-"+roommate.user_id);
                        if(roommate.user_role=="admin"){
                            roommate_item.addClass("admin");
                        }
                        if(roommate.gender=="Male"){
                            roommate_item.addClass("male");
                        }else if(roommate.gender=="Female"){
                            roommate_item.addClass("female");
                        }
                        if(time-roommate.check_timeout<CHECK_TIME/1000*1.5){
                            roommate_item.addClass("logged-in");
                        }else{
                            roommate_item.addClass("logged-out");
                        }
                        roommate_item.find("span.name").text(roommate.name);
                        roommate_item.appendTo(".roommate-list");
                    }
                })
                $(".room-user-list .list-group-item .state").text("0");
                $(".roommate-list .roommate-list-item .new-message-count").text("");
                
                new_messages.map(msage=>{
                    if(msage.from==active_roommate){
                        sender = user_list.find(item=>item.user_id==msage.from);
                        var mi = CHAT_ITEM.clone();
                        mi.addClass("chat-item other-user").find(".name").text(sender.name);                
                        var dateString = formatDateTime(msage.time*1000);
                        mi.find(".date-time").text(dateString);
                        mi.find(".message").text(msage.content);
                        mi.appendTo(".chat-list");
                        chatScrollBottom();
                    }else{
                        i = +$("#user-"+msage.from+" .state").text();
                        $("#user-"+msage.from+" .state").text(i+1);
                        
                        i = +$("#roommate-"+msage.from+" .new-message-count").text();
                        $("#roommate-"+msage.from+" .new-message-count").text(i+1);
                    }
                })
                
            }
        })
    }
    //setInterval(getRoomUsers, 100);

})(jQuery);