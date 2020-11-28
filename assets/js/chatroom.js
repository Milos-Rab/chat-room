(function ($) {

    const USER_TAB_ITEM = $('<div class="tab-item">'
                        + '<div class="state"></div>'
                        + '<div class="content">'
                            + '<div class="name  d-flex justify-content-between">'
                                + '<span class="name"></span>'
                                + '<span class="dismiss">x</span>'
                            + '</div>'
                            + '<div class="last-message d-flex justify-content-between">'
                                + '<span class="last-message-content"></span>'
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
                                + '<span class="badge badge-primary badge-pill state">o</span>'
                                + '</li>');
                                
    

    function chatScrollBottom(){
        scroll_height = $(".chat-list")[0].scrollHeight - $('.chat-list').height();
        $('.chat-list').animate({ scrollTop: scroll_height }, 200);
    }
    chatScrollBottom();

    $("form.message-sender").submit(function(event){
        event.preventDefault();
        message = $(this).find("input").val();
        $(this).find("input").val("");

        var mi = CHAT_ITEM.clone();
        mi.addClass("chat-item your-message").find(".name").text("You");
        mi.find(".message").text(message);
        mi.appendTo(".chat-list");
        chatScrollBottom();

    })

})(jQuery);