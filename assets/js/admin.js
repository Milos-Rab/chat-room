$(function() {

    var dates = {
        convert:function(d) {
            // Converts the date in d to a date-object. The input can be:
            //   a date object: returned without modification
            //  an array      : Interpreted as [year,month,day]. NOTE: month is 0-11.
            //   a number     : Interpreted as number of milliseconds
            //                  since 1 Jan 1970 (a timestamp) 
            //   a string     : Any format supported by the javascript engine, like
            //                  "YYYY/MM/DD", "MM/DD/YYYY", "Jan 31 2009" etc.
            //  an object     : Interpreted as an object with year, month and date
            //                  attributes.  **NOTE** month is 0-11.
            return (
                d.constructor === Date ? d :
                d.constructor === Array ? new Date(d[0],d[1],d[2]) :
                d.constructor === Number ? new Date(d) :
                d.constructor === String ? new Date(d) :
                typeof d === "object" ? new Date(d.year,d.month,d.date) :
                NaN
            );
        },
        compare:function(a,b) {
            // Compare two dates (could be of any type supported by the convert
            // function above) and returns:
            //  -1 : if a < b
            //   0 : if a = b
            //   1 : if a > b
            // NaN : if a or b is an illegal date
            // NOTE: The code inside isFinite does an assignment (=).
            return (
                isFinite(a=this.convert(a).valueOf()) &&
                isFinite(b=this.convert(b).valueOf()) ?
                (a>b)-(a<b) :
                NaN
            );
        },
        inRange:function(d,start,end) {
            // Checks if date in d is between dates in start and end.
            // Returns a boolean or NaN:
            //    true  : if d is between start and end (inclusive)
            //    false : if d is before start or after end
            //    NaN   : if one or more of the dates is illegal.
            // NOTE: The code inside isFinite does an assignment (=).
           return (
                isFinite(d=this.convert(d).valueOf()) &&
                isFinite(start=this.convert(start).valueOf()) &&
                isFinite(end=this.convert(end).valueOf()) ?
                start <= d && d <= end :
                NaN
            );
        }
    }

    var room_table=$("table#room_table").DataTable();
    var user_table=$("table#user_table").DataTable();
    var message_table=$("table#message_table").DataTable({
        columns:[
            {name: "check"},
            {name: "from"},
            {name: "to"},
            {name: "content"},
            {name: "sendingDate", type: "date"}
        ]
    });
    var ip_table=$("table#ip_table").DataTable();

    $.fn.dataTable.ext.search.push(
        function(settings, searchData, index, rowData, counter){
            var start = new Date($("#start-date").val()+" 00:00:00");
            var end = new Date($("#end-date").val()+" 23:59:59");
            var date = new Date(searchData[4]);
            console.log(start, end, date);
            return dates.inRange(date, start, end);
    })

    var today = new Date();
    $("#end-date").val(today.toISOString().substr(0, 10));

    $.ajax({
        url: "./admin_process.php",
        type: "POST",
        data: {"type":"SCAN_MESSAGE"},
        dataType: "json",
        success: function(res){
            console.log(res);
        }
    })
    
    $('#roomEditModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var room_name = button.data('roomname')
        var room_id = button.data('roomid')
        
        var modal = $(this)
        if(room_name==""){
            modal.find('.modal-title').text('Add New Room')
            modal.find('.modal-footer .btn#save').text("Add New Room").removeClass("edit-room").addClass("add-room");
        }else{
            modal.find('.modal-title').text('Edit Room')
            modal.find('.modal-footer .btn#save').text("Save Change").addClass("edit-room").removeClass("add-room");
        }
        modal.find('.modal-body input#room-name').val(room_name);
        modal.find('.modal-body input#room-id').val(room_id);
    })

    $("#roomEditModal").on("click", "#save.add-room", function(){
        const room_name = $("#roomEditModal .modal-body input#room-name").val();
        if(room_name.trim()){
            $.ajax({
                url:"./admin_process.php",
                type: "POST",
                data: {type: "ADD_ROOM", room_name:room_name},
                dataType: "json",
                success: function(res){
                    console.log(res);
                    if(res.result=="success"){
                        $("#roomEditModal").modal("hide");
                        new_row = room_table
                            .row.add([""+res.data.insert_id, room_name, '0', '<button class="btn btn-sm btn-success" id="edit" type="button" data-toggle="modal" data-target="#roomEditModal" data-roomname="'+room_name+'" data-roomid="'+res.data.insert_id+'">Edit</button> <button class="btn btn-sm btn-danger" id="delete" type="button">Delete</button>'])
                            .draw()
                            .node();
                        $(new_row).attr("id",""+res.data.insert_id);
                    }else if(res.result=="error"){
                        window.alert(res.data.message);
                    }
                }
            })
        }
    })

    $("#roomEditModal").on("click", "#save.edit-room", function(){
        let room_id = $("#roomEditModal .modal-body input#room-id").val();
        let room_name = $("#roomEditModal .modal-body input#room-name").val();
        if(room_name.trim()){
            $.ajax({
                url: "./admin_process.php",
                type: "POST",
                data: {type:"EDIT_ROOM", room_id:room_id, new_name: room_name},
                dataType: "json",
                success: function(res){
                    console.log(res);   
                    if(res.result=="success"){
                        $("#roomEditModal").modal("hide");
                        edited_row = room_table.row($("tr#"+room_id));
                        let rowindex = edited_row.index();
                        room_table.cell({row:rowindex, column:1}).data(room_name).draw();                        
                        $(edited_row.node()).find("td .btn#edit").data("roomname",room_name);
                    }else if(res.result=="error"){
                        window.alert(res.data.message);
                    }
                }
            })
        }
    })

    $("#room_table tbody").on("click", "button#delete", function(){
        console.log();
        //$(this).parents("tr").remove();
        if(window.confirm("Are you sure to delete a room?")){
            room_id = $(this).parents("tr").attr("id");
            $.ajax({
                url: "./admin_process.php",
                type: "POST",
                data: {type:"DELETE_ROOM", room_id:room_id},
                dataType: "json",
                success: function(res){
                    if(res.result=="success"){
                        $("#roomEditModal").modal("hide");
                        room_table.row($("tr#"+room_id)).remove().draw();
                    }else if(res.result=="error"){
                        window.alert(res.data.message);
                    }
                }
            })
        }
    })

    $("#ip_table tbody").on("change", "tr select", function(){
        const action = $(this).val();
        if(action=="blocked") var origin="opened";
        else if(action=="opened") var origin = "blocked";
        var t = $(this);
        const ip_address=$(this).parents("tr").children("td:eq(0)").text();
        $.ajax({
            url: "./admin_process.php",
            type: "post",
            dataType: "json",
            data: {type: "IP_OPERATE", action:action, ip_address: ip_address},
            success: function(res){
                if(res.result=="success"){
                    
                }else if(res.result=="error"){
                    t.val(origin);
                    window.alert("error");
                }
            }
        })
    })

    function loadMessages(){
        $("#v-pills-messages .loader-container").removeClass("hidden");
        $(".message-table-pan").addClass("loading");
        $.ajax({
            url: "./admin_process.php",
            type: "post",
            dataType: "json",
            data: {type: "GET_MESSAGES", from:"", to:"", start_date:"", end_date:""},
            success: function(res){
                const users = res.users;
                const messages = res.messages;
                messages.map(message=>{
                    const from=users.find(user=>user.user_id==message.from)
                    const to = users.find(user=>user.user_id==message.to)
                    const user_from=from.name+"("+from.gender+","+from.age+""+")";
                    const user_to=to.name+"("+to.gender+","+to.age+""+")";
                    const content = message.content;
                    const date = formatDateTime(message.time);
                    rowNode= message_table.
                        row.add(['<input type="checkbox">', user_from, user_to, content, date])
                        .draw()
                        .node();
                    $(rowNode).attr("id", message.id);
                })
                $("#v-pills-messages .loader-container").addClass("hidden");
                $(".message-table-pan").removeClass("loading");
            }
        })
    }

    loadMessages();
    function formatDateTime(mills){
        return new Date(mills*1000).toLocaleString();//.substring(0, 25);
    }
  
    $("select#message_from").change(function(){
        key = this.value;
        message_table.column(1).search(key).draw();
        $("input[type=checkbox]#check-all").prop("checked", false);
        message_check(false);
    })

    $("select#message_to").change(function(){
        key = this.value;
        message_table.column(2).search(key).draw();
        $("input[type=checkbox]#check-all").prop("checked", false);
        message_check(false);        
    })

    $("#start-date,#end-date").change(function(){
        message_table.draw();
    })


    $("input[type=checkbox]#check-all").on('click',function(e){
        const t = $(this).prop("checked");
        message_check(t);
    })

    function message_check(t){
        rows=message_table.rows( { filter : 'applied'} ).nodes();
        if(t){
            rows.map(row=>{
                check = $(row).find("td input[type=checkbox]").prop("checked", true);
            })
        }else{
            rows.map(row=>{
                check = $(row).find("td input[type=checkbox]").prop("checked", false);
            })
        }
    }

    $("button#delete-message").on('click', function(){
        checked_rows = [];
        rows=message_table.rows( { filter : 'applied'} ).nodes();
        rows.map(row=>{
            check = $(row).find("td input[type=checkbox]").prop("checked");
            if(check){
                checked_rows.push($(row).attr("id"));
            }
        })
        if(checked_rows.length!=0){
            
            $("#v-pills-messages .loader-container").removeClass("hidden");
            $(".message-table-pan").addClass("loading");
            $.ajax({
                url: "./admin_process.php",
                type: "POST",
                data:{type:"DELETE_MESSAGE", checked_rows: checked_rows},
                success: function(res){
                    if(res=="success"){
                        checked_rows.map(checked=>{
                            message_table.row("#"+checked).remove();
                        })
                        message_table.draw();
                        
                        $("#v-pills-messages .loader-container").addClass("hidden");
                        $(".message-table-pan").removeClass("loading");
                    }
                }
            })
        }
    })

});
