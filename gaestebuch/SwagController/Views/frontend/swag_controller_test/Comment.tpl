
<form method='post' action='{url action=sendComment}'>
    <div class="blog--comments-opinion">
        <input name="username" type='text' class="input--field" placeholder="Benutzername">
        <textarea name='comment' class="input--field" rows='5' placeholder="Gästebucheintrag"></textarea>
    </div>
    <input type='submit' class="btn is--primary" name='send' value='send'>
</form>
<div class="col-md-3">
    <h3>Gästebucheintrag</h3>
    <ul class="comments--list list--unstyled">
        {foreach $commentaries as $commentary}
            {strip}
                <li class="list--entry">
                    <div style="border: 1px solid #dadae5;">
                        <div class="entry--content">
                            <p>{$commentary->getComment()}</p>
                        </div>
                        <div class="entry--meta">
                            <div class="meta--author" style="margin-top: -20px;">
                                <p>Von: {$commentary->getUsername()}</p>
                            </div>
                            <div class="meta--date" style="margin-top: -30px;">
                                <p>Am: {$commentary->getSenddate()|date:date_long}</p>
                            </div>
                        </div>
                    </div>
                </li>
            {/strip}
        {/foreach}
    </ul>
</div>

