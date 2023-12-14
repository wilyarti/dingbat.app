<div>
    <div>
        <p class="text-xl pb-3 flex items-center">
        <div class="text-center text-2xl">
            Help
            <br/>
        </div>
        <br/>
    </div>
    I will be adding help pages soon. If your stuck open this window and use the Facebook chat bot. <br/><br/>

    Or email: dev@zeroultra.io
        <br/><br/>
    <a href="https://blog.dingbat.app/index.php/category/help/">Visit the help section of the Dingbat blog.</a>
    <br/><br/>

    <div class="text-xl border-b-4 w-auto border-indigo-400">Change Log</div>
    <br/>
    <span
        class="text-gray-400 text-lg pb-2 border-pink-400">Saturday, 2nd of October 2021</span>
    <br/>
    Starting Strength plans are now loaded. Fixed up bug with measurement entry tab.
    <br/>
    <br/>
    <span
        class="text-gray-400 text-lg pb-2 border-pink-400">Saturday, 19th of June 2021</span>
    <br/>
    Custom workouts are now supported. These workouts will not contribute to your plan statistics,<br/>
    but they are a quick and simple way of recording a sessions without creating a plan.
    <br/>
    <br/>
    <span
        class="text-gray-400 text-lg pb-2 border-pink-400">Friday, 18th of June 2021</span>
    <br/>Added extra plans:
    <ul>
        <li> - nSuns 4 Day Split</li>
        <li> - nSuns 5 Day Split</li>
        <li> - Metallicadpa v3.04 PPL 12 Weeks</li>
    </ul>
    <br/>
    Fixed bugs in the Create Plan Wizard.
    @include('partials.chat-bot.bot')
</div>
<script>
    var chatbox = document.getElementById('fb-customer-chat');
    chatbox.setAttribute("page_id", "111131551202328");
    chatbox.setAttribute("attribution", "biz_inbox");
    window.fbAsyncInit = function () {
        FB.init({
            xfbml: true,
            version: 'v11.0'
        });
    };

    (function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s);
        js.id = id;
        js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>
