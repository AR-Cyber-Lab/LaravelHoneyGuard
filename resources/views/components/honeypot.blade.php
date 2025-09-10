@php($ts = time())
<input type="text" name="{{ config('honeyguard.honeypot_field','website') }}" value="" style="position:absolute;left:-9999px;" tabindex="-1" autocomplete="off" />
<input type="hidden" name="{{ config('honeyguard.timestamp_field','hp_ts') }}" value="{{ $ts }}">
