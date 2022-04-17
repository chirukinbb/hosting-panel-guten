<div class="card p-3" id="thumbnail">
    <label>Announce to:</label>
    @if(env('TELEGRAM_CHANNEL_TOKEN'))
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="telegram_channel" name="telegram_channel" checked value="1">
            <label class="form-check-label" for="telegram_channel">Telegram Channel</label>
        </div>
    @endif
    @if(env('FACEBOOK_PAGE_ID'))
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="telegram_channel" name="facebook_group" checked value="1">
            <label class="form-check-label" for="telegram_channel">Facebook Group</label>
        </div>
    @endif
</div>
