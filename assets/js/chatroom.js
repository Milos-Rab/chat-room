(function ($) {
    const LOGIN_TEST = 15000;
    const NEW_MESSAGE = 1000;
    const NEW_USER = 10000;
    const CHECK_OUT = 10000;
    const CHECK_NEW_MESSAGE = 3000;
    const ROOMMATE_ITEM = $('<div class="roommate-list-item logged-out">'
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

    const USER_LIST_ITEM = $('<li class="list-group-item d-flex justify-content-between logged-out">'
                            + '<span class="name-ag"></span>'
                            + '<span class="badge badge-primary badge-pill state">o</span>'
                        + '</li>');
               
    const user_list = [];
    const roommate_list = [];
    
    function chatScrollBottom(){
        scroll_height = $(".chat-list")[0].scrollHeight - $('.chat-list').height();
        $('.chat-list').animate({ scrollTop: scroll_height }, 200);
    }

    $("form.message-sender").submit(function(event){
        event.preventDefault();
        message = $(this).find("input").val();
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

        id=$(this).attr("id").split("-");
        roommate_id = id[1];
        roommate = roommate_list.filter(item=>item.roommate===roommate_id);
        if(roommate.length===0){
            $.ajax({
                url: "./process.php",
                type: "post",
                data: {type:"ADD_ROOMMATE", roommate_id:roommate_id},
                success: function(res){
                    if(res=="success"){
                        roommate_list.push({roommate:roommate_id});
                        let $roommate_item = ROOMMATE_ITEM.clone();
                        let user = user_list.filter(ul=>ul.user_id===roommate_id)[0];

                        $(".roommate-list-item").removeClass("active");
                        $roommate_item.addClass("active").attr("id", "roommate-"+roommate_id).find("span.name").text(user.name);
                        $roommate_item.appendTo(".roommate-list");
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
                $(".roommate-list").find(".badge").text("");
                $(".chat .loader-container").addClass("hidden");
                addNewMessages(res, active_roommate, active_name);
                chatScrollBottom();
            }
        })
    }

    getRoomUsers();
    function getRoomUsers(){
        $.ajax({
            url: "./process.php",
            type: "POST",
            data: {type: "GET_ROOM_USERS"},
            dataType: "json",
            success: function(res){
                Object.assign(user_list, res);
                user_list.map(item=>{
                    let $user_list_item = USER_LIST_ITEM.clone();
                    if(item.role_name=="admin"){
                        $user_list_item.addClass("admin").removeClass("logged-out");
                    }
                    $user_list_item.attr("id", "user-"+item.user_id).find(".name-ag").text(item.name+" ("+item.age+") "+item.gender);
                    $user_list_item.appendTo(".room-user-list");
                })
                $(".room-user-list .loader-container").addClass("hidden");
            }
        })
    }

    getRoommate();
    function getRoommate(){
        $.ajax({
            url: "./process.php",
            type: "POST",
            data: {type: "GET_ROOMMATE"},
            dataType: "json",
            success: function(res){
                Object.assign(roommate_list, res);
                roommate_list.map(item=>{
                    let $roommate_item = ROOMMATE_ITEM.clone();
                    let user = user_list.filter(ul=>ul.user_id==item.roommate)[0];
                    if(user.role_name=="admin"){
                        $roommate_item.addClass("admin").removeClass("logged-out");
                    }
                    $roommate_item.attr("id", "roommate-"+user.user_id).find("span.name").text(user.name);
                    $roommate_item.appendTo(".roommate-list");
                    $("#user-"+item.roommate).addClass("active");
                });
                $(".roommate-list .loader-container").addClass("hidden");
            }
        })
    }
    
    // new message test
    setInterval(() => {
        if($(".roommate-list-item.active").attr("id")){
            id = $(".roommate-list-item.active").attr("id");
            active_roommate = id.split("-")[1];
            active_name = $(".roommate-list-item.active").find("span.name").text();
            $.ajax({
                url: "./process.php",
                type: "post",
                data: {type: "GET_NEW_MESSAGES", active_roommate: active_roommate},
                dataType: "json",
                success: function(res){
                    if(res.length>0){
                        addNewMessages(res, active_roommate, active_name);
                        chatScrollBottom();
                    }
                }
            })
        }
    }, NEW_MESSAGE);

    // 
    setInterval(() => {
        
    }, NEW_USER);

    function checkOut(){
        $.ajax({
            url:"./process.php",
            type:"POST",
            data:{type:"CHECK_OUT"},
            dataType: "json",
            success: function(res){
                res.map(item=>{
                    if(item.timediff*1000>CHECK_OUT*1.5){
                        $("#user-"+item.user_id).removeClass("logged-in");
                        $("#user-"+item.user_id).addClass("logged-out");
                        $("#roommate-"+item.user_id).removeClass("logged-in");
                        $("#roommate-"+item.user_id).addClass("logged-out");
                    }else{
                        $("#user-"+item.user_id).removeClass("logged-out");
                        $("#user-"+item.user_id).addClass("logged-in");
                        $("#roommate-"+item.user_id).removeClass("logged-out");
                        $("#roommate-"+item.user_id).addClass("logged-in");
                    }
                })
            }
        });
    }
    checkOut();
    setInterval(checkOut, CHECK_OUT);
    
    // 
    function checkNewMessages(){
        $.ajax({
            url: "./process.php",
            type: "POST",
            data: {type: "CHECK_NEW_MESSAGES"},
            dataType: "json",
            success: function(res){
                
                id = $(".roommate-list-item.active").attr("id");
                active_roommate=null;
                if(id){
                    active_roommate = id.split("-")[1];
                }
                //console.log(res);
                roommate_list.map((roommate=>{
                    new_messages = res.filter(message=>message.from == roommate.roommate);
                    if(new_messages.length!=0 && roommate!=active_roommate){
                        $(".roommate-list-item#roommate-"+roommate.roommate).find(".new-message-count").text(new_messages.length);
                        $("#user-"+roommate.roommate).find(".state").text(new_messages.length);
                        
                    }
                }))
            }
        })
    }
    checkNewMessages();
    setInterval(checkNewMessages, CHECK_NEW_MESSAGE);
    
})(jQuery);