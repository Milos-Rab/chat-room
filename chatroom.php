<?php
    session_start();

    include './template/header.php';
    
    $page = "chatroom";
?>

<div class="chat-container">
    <div class="tabs">
        <div class="user-search"></div>
        <div class="user-list">
            <div class="tab-item admin">
                <div class="state"></div>
                <div class="content">
                    <div class="name  d-flex justify-content-between">
                        <span class="name">Mike</span>
                        <span class="dismiss">x</span>
                    </div>
                    <div class="last-message d-flex justify-content-between">
                        <span class="last-message">how are you?</span>
                        <span  class="badge badge-primary badge-pill new-message-count">125</span>
                    </div>
                </div>
            </div>
            <div class="tab-item logged-out">
                <div class="state"></div>
                <div class="content">
                    <div class="name  d-flex justify-content-between">
                        <span class="name">Mike</span>
                        <span class="dismiss">x</span>
                    </div>
                    <div class="last-message d-flex justify-content-between">
                        <span class="last-message">how are you?</span>
                        <span  class="badge badge-primary badge-pill new-message-count">125</span>
                    </div>
                </div>
            </div>
            <div class="tab-item logged-in active">
                <div class="state"></div>
                <div class="content">
                    <div class="name  d-flex justify-content-between">
                        <span class="name">Mike</span>
                        <span class="dismiss">x</span>
                    </div>
                    <div class="last-message d-flex justify-content-between">
                        <span class="last-message-content">how are you?</span>
                        <span  class="badge badge-primary badge-pill new-message-count">125</span>
                    </div>
                </div>
            </div>
            <div class="tab-item logged-in">
                <div class="state"></div>
                <div class="content">
                    <div class="name  d-flex justify-content-between">
                        <span class="name">Mike</span>
                        <span class="dismiss">x</span>
                    </div>
                    <div class="last-message d-flex justify-content-between">
                        <span class="last-message">how are you?</span>
                        <span  class="badge badge-primary badge-pill new-message-count">125</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="chat-pan">
        <div class="chat">
            <div class="chat-list">
                <div class="chat-item other-user">
                    <div class="name-time">
                        <span class="name">Mike</span>, <span class="date-time">2020-12-4 3:8:32</span>
                    </div>
                    <div class="message">
                        How are you?
                    </div>
                </div>
                <div class="chat-item your-message">
                    <div class="name-time">
                        <span class="name">You</span>, <span class="date-time">2020-12-4 3:8:32</span>
                    </div>
                    <div class="message">
                        Hello, Nice to meet you.
                        I am a Web/Mobile expert from Russia
                        I have strong ability to handle any projects perfectly.
                        You can see my ability via any tasks quickly.
                        Do you have any projects perhaps?
                    </div>
                </div>
                <div class="chat-item other-user">
                    <div class="name-time">
                        <span class="name">Mike</span>, <span class="date-time">2020-12-4 3:8:32</span>
                    </div>
                    <div class="message">
                        How are you?
                    </div>
                </div>
                <div class="chat-item your-message">
                    <div class="name-time">
                        <span class="name">You</span>, <span class="date-time">2020-12-4 3:8:32</span>
                    </div>
                    <div class="message">
                        Hello, Nice to meet you.
                        I am a Web/Mobile expert from Russia
                        I have strong ability to handle any projects perfectly.
                        You can see my ability via any tasks quickly.
                        Do you have any projects perhaps?
                    </div>
                </div><div class="chat-item other-user">
                    <div class="name-time">
                        <span class="name">Mike</span>, <span class="date-time">2020-12-4 3:8:32</span>
                    </div>
                    <div class="message">
                        How are you?
                    </div>
                </div>
                <div class="chat-item your-message">
                    <div class="name-time">
                        <span class="name">You</span>, <span class="date-time">2020-12-4 3:8:32</span>
                    </div>
                    <div class="message">
                        Hello, Nice to meet you.
                        I am a Web/Mobile expert from Russia
                        I have strong ability to handle any projects perfectly.
                        You can see my ability via any tasks quickly.
                        Do you have any projects perhaps?
                    </div>
                </div><div class="chat-item other-user">
                    <div class="name-time">
                        <span class="name">Mike</span>, <span class="date-time">2020-12-4 3:8:32</span>
                    </div>
                    <div class="message">
                        How are you?
                    </div>
                </div>
                <div class="chat-item your-message">
                    <div class="name-time">
                        <span class="name">You</span>, <span class="date-time">2020-12-4 3:8:32</span>
                    </div>
                    <div class="message">
                        Hello, Nice to meet you.
                        I am a Web/Mobile expert from Russia
                        I have strong ability to handle any projects perfectly.
                        You can see my ability via any tasks quickly.
                        Do you have any projects perhaps?
                    </div>
                </div><div class="chat-item other-user">
                    <div class="name-time">
                        <span class="name">Mike</span>, <span class="date-time">2020-12-4 3:8:32</span>
                    </div>
                    <div class="message">
                        How are you?
                    </div>
                </div>
                <div class="chat-item your-message">
                    <div class="name-time">
                        <span class="name">You</span>, <span class="date-time">2020-12-4 3:8:32</span>
                    </div>
                    <div class="message">
                        Hello, Nice to meet you.
                        I am a Web/Mobile expert from Russia
                        I have strong ability to handle any projects perfectly.
                        You can see my ability via any tasks quickly.
                        Do you have any projects perhaps?
                    </div>
                </div>
                <div class="chat-item other-user">
                    <div class="name-time">
                        <span class="name">Mike</span>, <span class="date-time">2020-12-4 3:8:32</span>
                    </div>
                    <div class="message">
                        How are you?
                    </div>
                </div>
                <div class="chat-item your-message">
                    <div class="name-time">
                        <span class="name">You</span>, <span class="date-time">2020-12-4 3:8:32</span>
                    </div>
                    <div class="message">
                        Hello, Nice to meet you.
                        I am a Web/Mobile expert from Russia
                        I have strong ability to handle any projects perfectly.
                        You can see my ability via any tasks quickly.
                        Do you have any projects perhaps?
                    </div>
                </div><div class="chat-item other-user">
                    <div class="name-time">
                        <span class="name">Mike</span>, <span class="date-time">2020-12-4 3:8:32</span>
                    </div>
                    <div class="message">
                        How are you?
                    </div>
                </div>
                <div class="chat-item your-message">
                    <div class="name-time">
                        <span class="name">You</span>, <span class="date-time">2020-12-4 3:8:32</span>
                    </div>
                    <div class="message">
                        Hello, Nice to meet you.
                        I am a Web/Mobile expert from Russia
                        I have strong ability to handle any projects perfectly.
                        You can see my ability via any tasks quickly.
                        Do you have any projects perhaps?
                    </div>
                </div><div class="chat-item other-user">
                    <div class="name-time">
                        <span class="name">Mike</span>, <span class="date-time">2020-12-4 3:8:32</span>
                    </div>
                    <div class="message">
                        How are you?
                    </div>
                </div>
                <div class="chat-item your-message">
                    <div class="name-time">
                        <span class="name">You</span>, <span class="date-time">2020-12-4 3:8:32</span>
                    </div>
                    <div class="message">
                        Hello, Nice to meet you.
                        I am a Web/Mobile expert from Russia
                        I have strong ability to handle any projects perfectly.
                        You can see my ability via any tasks quickly.
                        Do you have any projects perhaps?
                    </div>
                </div>
            </div>
        </div>
        <div class="send">
            <form class="message-sender">
                <div class="input-group mt-3">
                    <input type="text" class="form-control" placeholder="Type your message..." style="z-index:0">
                    <div class="input-group-append">
                        <button class="btn btn-success" type="button" style="z-index:1">Send</button>  
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="users">
        <div class="input-group mt-1 mb-1">
            <input type="text" class="form-control" placeholder="search..." style="z-index:0">
            <div class="input-group-append">
                <span class="input-group-text">@</span>
            </div>
        </div>
        <ul class="list-group list-group-flush all-user-list">
            <li class="list-group-item d-flex justify-content-between active admin">
                <span class="name-ag">Mike (23) M</span>
                <span class="badge badge-primary badge-pill state">o</span>
            </li>
            <li class="list-group-item d-flex justify-content-between active logged-in">
                <span class="name-ag">Mike (23) F</span>
                <span class="badge badge-primary badge-pill state">o</span>
            </li>
            <li class="list-group-item d-flex justify-content-between active logged-in">
                <span class="name-ag">Mike (23) M</span>
                <span class="badge badge-primary badge-pill state">o</span>
            </li>
            <li class="list-group-item d-flex justify-content-between active logged-out">
                <span class="name-ag">Mike (23) F</span>
                <span class="badge badge-primary badge-pill state">o</span>
            </li>
            <li class="list-group-item d-flex justify-content-between logged-in">
                <span class="name-ag">Mike (23) M</span>
                <span class="badge badge-primary badge-pill state">o</span>
            </li><li class="list-group-item d-flex justify-content-between">
                <span class="name-ag">Mike (23) M</span>
                <span class="badge badge-primary badge-pill state">o</span>
            </li>
            <li class="list-group-item d-flex justify-content-between logged-in">
                <span class="name-ag">Mike (23) F</span>
                <span class="badge badge-primary badge-pill state">o</span>
            </li>
            <li class="list-group-item d-flex justify-content-between logged-in">
                <span class="name-ag">Mike (23) M</span>
                <span class="badge badge-primary badge-pill state">o</span>
            </li>
            <li class="list-group-item d-flex justify-content-between logged-out">
                <span class="name-ag">Mike (23) F</span>
                <span class="badge badge-primary badge-pill state">o</span>
            </li>
            <li class="list-group-item d-flex justify-content-between logged-out">
                <span class="name-ag">Mike (23) F</span>
                <span class="badge badge-primary badge-pill state">o</span>
            </li>
            <li class="list-group-item d-flex justify-content-between logged-out">
                <span class="name-ag">Mike (23) F</span>
                <span class="badge badge-primary badge-pill state">o</span>
            </li>
            <li class="list-group-item d-flex justify-content-between logged-out">
                <span class="name-ag">Mike (23) F</span>
                <span class="badge badge-primary badge-pill state">o</span>
            </li>
        </ul>
        <div class="who-with-chat">
            <div class="who-item logged-in">
                <div class="state"></div>
                <div class="content">
                    <div class="name  d-flex justify-content-between">
                        <span class="name">Mike</span>
                        <span class="dismiss">x</span>
                    </div>
                    <div class="last-message d-flex justify-content-between">
                        <span class="last-message-content">how are you?</span>
                        <span class="badge badge-primary badge-pill new-message-count">125</span>
                    </div>
                </div>
            </div>
            <div class="who-item logged-in">
                <div class="state"></div>
                <div class="content">
                    <div class="name  d-flex justify-content-between">
                        <span class="name">Mike</span>
                        <span class="dismiss">x</span>
                    </div>
                    <div class="last-message d-flex justify-content-between">
                        <span class="last-message-content">how are you?</span>
                        <span class="badge badge-primary badge-pill new-message-count">125</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    include './template/footer.php';
?>