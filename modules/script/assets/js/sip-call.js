var oSipStack, oSipSessionRegister, oSipSessionCall, oSipSessionTransferCall;
var oNotifICall;
var oConfigCall;
var oReadyStateTimer;

window.onload = function () {
    divCallOptions = document.getElementById("divCallOptions");
    txtCallStatus = document.getElementById("txtCallStatus");

    // set debug level
    SIPml.setDebugLevel("info"); // error

    var preInit = function () {
        SIPml.init(function () {
            //alert('rot ebal1');


            if (!SIPml.isWebRtcSupported()) {
                // is it chrome?
                if (SIPml.getNavigatorFriendlyName() == 'chrome') {
                    if (confirm("You're using an old Chrome version or WebRTC is not enabled.\nDo you want to see how to enable WebRTC?")) {
                        window.location = 'http://www.webrtc.org/running-the-demos';
                    }

                    return;
                }
                else {
                    if (confirm("webrtc-everywhere extension is not installed. Do you want to install it?\nIMPORTANT: You must restart your browser after the installation.")) {
                        window.location = 'https://github.com/sarandogou/webrtc-everywhere';
                    }
                }
            }

            // checks for WebSocket support
            if (!SIPml.isWebSocketSupported()) {
                if (confirm('Your browser don\'t support WebSockets.\nDo you want to download a WebSocket-capable browser?')) {
                    window.location = 'https://www.google.com/intl/en/chrome/browser/';
                }
                return;
            }

            if (!SIPml.isWebRtcSupported()) {
                if (confirm('Your browser don\'t support WebRTC.\naudio calls will be disabled.\nDo you want to download a WebRTC-capable browser?')) {
                    window.location = 'https://www.google.com/intl/en/chrome/browser/';
                }
            }

            window['oConfigCall'] = {
                audio_remote: document.getElementById("audio_remote"),
                events_listener: {events: '*', listener: onSipEventSession},
                sip_caps: [
                    {name: '+g.oma.sip-im'},
                    {name: 'language', value: '\"en,ru\"'}
                ]
            };
        });
    };

    oReadyStateTimer = setInterval(function () {
        if (document.readyState === "complete") {
            clearInterval(oReadyStateTimer);
            preInit();
            sipRegister();
        }
    }, 500);
};


// sends SIP REGISTER request to login
function sipRegister() {

    var publicIdentityValue = 'sip:' + sipCredentials.public_identity;

    // catch exception for IE (DOM not ready)
    try {

        var o_impu = tsip_uri.prototype.Parse(publicIdentityValue);

        if (!o_impu || !o_impu.s_user_name || !o_impu.s_host) {
            txtRegStatus.innerHTML = "<b>[" + publicIdentityValue + "] is not a valid Public identity</b>";
            return;
        }

        // enable notifications if not already done
        if (window.webkitNotifications && window.webkitNotifications.checkPermission() != 0) {
            window.webkitNotifications.requestPermission();
        }

        // update debug level to be sure new values will be used if the user haven't updated the page
        SIPml.setDebugLevel("info");

        // create SIP stack
        oSipStack = new SIPml.Stack({
                realm: sipCredentials.realm, // mandatory: domain name
                impi: sipCredentials.private_identity, // mandatory: authorization name (IMS Private Identity)
                impu: publicIdentityValue, // mandatory: valid SIP Uri (IMS Public Identity)
                password: sipCredentials.password, // optional
                display_name: sipCredentials.display_name, // optional


                events_listener: {events: '*', listener: onSipEventStack},
                sip_headers: [
                    {name: 'User-Agent', value: 'IM-client/OMA1.0 sipML5-v1.2016.03.04'},
                    {name: 'Organization', value: 'Doubango Telecom'}
                ]
            }
        );
        if (oSipStack.start() != 0) {
            txtRegStatus.innerHTML = '<b>Failed to start the SIP stack</b>';
        }
        else return;
    }
    catch (e) {
        txtRegStatus.innerHTML = "<b>2:" + e + "</b>";
    }
}

// sends SIP REGISTER (expires=0) to logout
function sipUnRegister() {
    if (oSipStack) {
        oSipStack.stop(); // shutdown all sessions
    }
}

function sipCall(s_type) {

    var number = $('#script___call__perform_sip_call_to').val();

    if (oSipStack && !oSipSessionCall && !tsk_string_is_null_or_empty(number)) {

        btnCall.disabled = true;
        btnHangUp.disabled = false;

        console.log(oConfigCall);

        // create call session
        oSipSessionCall = oSipStack.newSession(s_type, oConfigCall);
        // make call
        if (oSipSessionCall.call(number) != 0) {
            oSipSessionCall = null;
            txtCallStatus.innerHTML = 'Failed to make call';
            btnCall.disabled = false;
            btnHangUp.disabled = true;
            return;
        }
    }
    else if (oSipSessionCall) {
        txtCallStatus.innerHTML = '<i>Connecting...</i>';
        oSipSessionCall.accept(oConfigCall);
    }
}

// transfers the call
function sipTransfer() {
    if (oSipSessionCall) {
        var s_destination = prompt('Enter destination number', '');
        if (!tsk_string_is_null_or_empty(s_destination)) {
            btnTransfer.disabled = true;
            if (oSipSessionCall.transfer(s_destination) != 0) {
                txtCallStatus.innerHTML = '<i>Call transfer failed</i>';
                btnTransfer.disabled = false;
                return;
            }
            txtCallStatus.innerHTML = '<i>Transfering the call...</i>';
        }
    }
}

// holds or resumes the call
function sipToggleHoldResume() {
    if (oSipSessionCall) {
        var i_ret;
        btnHoldResume.disabled = true;
        txtCallStatus.innerHTML = oSipSessionCall.bHeld ? '<i>Resuming the call...</i>' : '<i>Holding the call...</i>';
        i_ret = oSipSessionCall.bHeld ? oSipSessionCall.resume() : oSipSessionCall.hold();
        if (i_ret != 0) {
            txtCallStatus.innerHTML = '<i>Hold / Resume failed</i>';
            btnHoldResume.disabled = false;
            return;
        }
    }
}

// Mute or Unmute the call
function sipToggleMute() {
    if (oSipSessionCall) {
        var i_ret;
        var bMute = !oSipSessionCall.bMute;
        txtCallStatus.innerHTML = bMute ? '<i>Mute the call...</i>' : '<i>Unmute the call...</i>';
        i_ret = oSipSessionCall.mute('audio', bMute);
        if (i_ret != 0) {
            txtCallStatus.innerHTML = '<i>Mute / Unmute failed</i>';
            return;
        }
        oSipSessionCall.bMute = bMute;
        btnMute.value = bMute ? "Unmute" : "Mute";
    }
}

// terminates the call (SIP BYE or CANCEL)
function sipHangUp() {
    if (oSipSessionCall) {
        txtCallStatus.innerHTML = '<i>Terminating the call...</i>';
        oSipSessionCall.hangup({events_listener: {events: '*', listener: onSipEventSession}});
    }
}

function startRingTone() {
    try {
        ringtone.play();
    }
    catch (e) {
    }
}

function stopRingTone() {
    try {
        ringtone.pause();
    }
    catch (e) {
    }
}

function startRingbackTone() {
    try {
        ringbacktone.play();
    }
    catch (e) {
    }
}

function stopRingbackTone() {
    try {
        ringbacktone.pause();
    }
    catch (e) {
    }
}


function showNotifICall(s_number) {
    // permission already asked when we registered
    if (window.webkitNotifications && window.webkitNotifications.checkPermission() == 0) {
        if (oNotifICall) {
            oNotifICall.cancel();
        }
        oNotifICall = window.webkitNotifications.createNotification('images/sipml-34x39.png', 'Incaming call', 'Incoming call from ' + s_number);
        oNotifICall.onclose = function () {
            oNotifICall = null;
        };
        oNotifICall.show();
    }
}

function uiOnConnectionEvent(b_connected, b_connecting) { // should be enum: connecting, connected, terminating, terminated
    btnCall.disabled = !(b_connected && tsk_utils_have_webrtc() && tsk_utils_have_stream());
    btnHangUp.disabled = !oSipSessionCall;
}


function uiCallTerminated(s_description) {
    btnHangUp.value = 'HangUp';
    btnHoldResume.value = 'hold';
    btnMute.value = "Mute";
    btnCall.disabled = false;
    btnHangUp.disabled = true;
    if (window.btnBFCP) window.btnBFCP.disabled = true;

    oSipSessionCall = null;

    stopRingbackTone();
    stopRingTone();

    txtCallStatus.innerHTML = "<i>" + s_description + "</i>";
    divCallOptions.style.opacity = 0;

    if (oNotifICall) {
        oNotifICall.cancel();
        oNotifICall = null;
    }


    setTimeout(function () {
        if (!oSipSessionCall) txtCallStatus.innerHTML = '';
    }, 2500);
}

// Callback function for SIP Stacks
function onSipEventStack(e /*SIPml.Stack.Event*/) {
    tsk_utils_log_info('==stack event = ' + e.type);
    switch (e.type) {
        case 'started':
        {
            // catch exception for IE (DOM not ready)
            try {
                // LogIn (REGISTER) as soon as the stack finish starting
                oSipSessionRegister = this.newSession('register', {
                    expires: 200,
                    events_listener: {events: '*', listener: onSipEventSession},
                    sip_caps: [
                        {name: '+g.oma.sip-im', value: null},
                        //{ name: '+sip.ice' }, // rfc5768: FIXME doesn't work with Polycom TelePresence
                        {name: '+audio', value: null},
                        {name: 'language', value: '\"en,fr\"'}
                    ]
                });
                oSipSessionRegister.register();
            }
            catch (e) {
                txtRegStatus.value = txtRegStatus.innerHTML = "<b>1:" + e + "</b>";
            }
            break;
        }
        case 'stopping':
        case 'stopped':
        case 'failed_to_start':
        case 'failed_to_stop':
        {
            var bFailure = (e.type == 'failed_to_start') || (e.type == 'failed_to_stop');
            oSipStack = null;
            oSipSessionRegister = null;
            oSipSessionCall = null;

            uiOnConnectionEvent(false, false);

            stopRingbackTone();
            stopRingTone();

            divCallOptions.style.opacity = 0;

            txtCallStatus.innerHTML = '';
            txtRegStatus.innerHTML = bFailure ? "<i>Disconnected: <b>" + e.description + "</b></i>" : "<i>Disconnected</i>";
            break;
        }

        case 'i_new_call':
        {
            if (oSipSessionCall) {
                // do not accept the incoming call if we're already 'in call'
                e.newSession.hangup(); // comment this line for multi-line support
            }
            else {
                oSipSessionCall = e.newSession;
                // start listening for events
                oSipSessionCall.setConfiguration(oConfigCall);
                btnHangUp.value = 'Reject';
                btnCall.disabled = false;
                btnHangUp.disabled = false;

                startRingTone();

                var sRemoteNumber = (oSipSessionCall.getRemoteFriendlyName() || 'unknown');
                txtCallStatus.innerHTML = "<i>Incoming call from [<b>" + sRemoteNumber + "</b>]</i>";
                showNotifICall(sRemoteNumber);
            }
            break;
        }

        case 'm_permission_requested':
        {
            break;
        }
        case 'm_permission_accepted':
        case 'm_permission_refused':
        {
            if (e.type == 'm_permission_refused') {
                uiCallTerminated('Media stream permission denied');
            }
            break;
        }

        case 'starting':
        default:
            break;
    }
};

// Callback function for SIP sessions (INVITE, REGISTER, MESSAGE...)
function onSipEventSession(e /* SIPml.Session.Event */) {
    tsk_utils_log_info('==session event = ' + e.type);

    switch (e.type) {
        case 'connecting':
        case 'connected':
        {
            var bConnected = (e.type == 'connected');
            if (e.session == oSipSessionRegister) {
                uiOnConnectionEvent(bConnected, !bConnected);
                txtRegStatus.innerHTML = "<i>" + e.description + "</i>";
            }
            else if (e.session == oSipSessionCall) {
                btnHangUp.value = 'HangUp';
                btnCall.disabled = true;
                btnHangUp.disabled = false;
                btnTransfer.disabled = false;
                if (window.btnBFCP) window.btnBFCP.disabled = false;

                if (bConnected) {
                    stopRingbackTone();
                    stopRingTone();

                    if (oNotifICall) {
                        oNotifICall.cancel();
                        oNotifICall = null;
                    }
                }

                txtCallStatus.innerHTML = "<i>" + e.description + "</i>";
                divCallOptions.style.opacity = bConnected ? 1 : 0;
            }
            break;
        } // 'connecting' | 'connected'
        case 'terminating':
        case 'terminated':
        {
            if (e.session == oSipSessionRegister) {
                uiOnConnectionEvent(false, false);

                oSipSessionCall = null;
                oSipSessionRegister = null;

                txtRegStatus.innerHTML = "<i>" + e.description + "</i>";
            }
            else if (e.session == oSipSessionCall) {
                uiCallTerminated(e.description);
            }
            break;
        } // 'terminating' | 'terminated'

        case 'm_stream_audio_local_added':
        case 'm_stream_audio_local_removed':
        case 'm_stream_audio_remote_added':
        case 'm_stream_audio_remote_removed':
        {
            break;
        }

        case 'i_ect_new_call':
        {
            oSipSessionTransferCall = e.session;
            break;
        }

        case 'i_ao_request':
        {
            if (e.session == oSipSessionCall) {
                var iSipResponseCode = e.getSipResponseCode();
                if (iSipResponseCode == 180 || iSipResponseCode == 183) {
                    startRingbackTone();
                    txtCallStatus.innerHTML = '<i>Remote ringing...</i>';
                }
            }
            break;
        }

        case 'm_early_media':
        {
            if (e.session == oSipSessionCall) {
                stopRingbackTone();
                stopRingTone();
                txtCallStatus.innerHTML = '<i>Early media started</i>';
            }
            break;
        }

        case 'm_local_hold_ok':
        {
            if (e.session == oSipSessionCall) {
                if (oSipSessionCall.bTransfering) {
                    oSipSessionCall.bTransfering = false;
                }
                btnHoldResume.value = 'Resume';
                btnHoldResume.disabled = false;
                txtCallStatus.innerHTML = '<i>Call placed on hold</i>';
                oSipSessionCall.bHeld = true;
            }
            break;
        }
        case 'm_local_hold_nok':
        {
            if (e.session == oSipSessionCall) {
                oSipSessionCall.bTransfering = false;
                btnHoldResume.value = 'Hold';
                btnHoldResume.disabled = false;
                txtCallStatus.innerHTML = '<i>Failed to place remote party on hold</i>';
            }
            break;
        }
        case 'm_local_resume_ok':
        {
            if (e.session == oSipSessionCall) {
                oSipSessionCall.bTransfering = false;
                btnHoldResume.value = 'Hold';
                btnHoldResume.disabled = false;
                txtCallStatus.innerHTML = '<i>Call taken off hold</i>';
                oSipSessionCall.bHeld = false;
            }
            break;
        }
        case 'm_local_resume_nok':
        {
            if (e.session == oSipSessionCall) {
                oSipSessionCall.bTransfering = false;
                btnHoldResume.disabled = false;
                txtCallStatus.innerHTML = '<i>Failed to unhold call</i>';
            }
            break;
        }
        case 'm_remote_hold':
        {
            if (e.session == oSipSessionCall) {
                txtCallStatus.innerHTML = '<i>Placed on hold by remote party</i>';
            }
            break;
        }
        case 'm_remote_resume':
        {
            if (e.session == oSipSessionCall) {
                txtCallStatus.innerHTML = '<i>Taken off hold by remote party</i>';
            }
            break;
        }
        case 'm_bfcp_info':
        {
            if (e.session == oSipSessionCall) {
                txtCallStatus.innerHTML = 'BFCP Info: <i>' + e.description + '</i>';
            }
            break;
        }

        case 'o_ect_trying':
        {
            if (e.session == oSipSessionCall) {
                txtCallStatus.innerHTML = '<i>Call transfer in progress...</i>';
            }
            break;
        }
        case 'o_ect_accepted':
        {
            if (e.session == oSipSessionCall) {
                txtCallStatus.innerHTML = '<i>Call transfer accepted</i>';
            }
            break;
        }
        case 'o_ect_completed':
        case 'i_ect_completed':
        {
            if (e.session == oSipSessionCall) {
                txtCallStatus.innerHTML = '<i>Call transfer completed</i>';
                btnTransfer.disabled = false;
                if (oSipSessionTransferCall) {
                    oSipSessionCall = oSipSessionTransferCall;
                }
                oSipSessionTransferCall = null;
            }
            break;
        }
        case 'o_ect_failed':
        case 'i_ect_failed':
        {
            if (e.session == oSipSessionCall) {
                txtCallStatus.innerHTML = '<i>Call transfer failed</i>';
                btnTransfer.disabled = false;
            }
            break;
        }
        case 'o_ect_notify':
        case 'i_ect_notify':
        {
            if (e.session == oSipSessionCall) {
                txtCallStatus.innerHTML = "<i>Call Transfer: <b>" + e.getSipResponseCode() + " " + e.description + "</b></i>";
                if (e.getSipResponseCode() >= 300) {
                    if (oSipSessionCall.bHeld) {
                        oSipSessionCall.resume();
                    }
                    btnTransfer.disabled = false;
                }
            }
            break;
        }
        case 'i_ect_requested':
        {
            if (e.session == oSipSessionCall) {
                var s_message = "Do you accept call transfer to [" + e.getTransferDestinationFriendlyName() + "]?";//FIXME
                if (confirm(s_message)) {
                    txtCallStatus.innerHTML = "<i>Call transfer in progress...</i>";
                    oSipSessionCall.acceptTransfer();
                    break;
                }
                oSipSessionCall.rejectTransfer();
            }
            break;
        }
    }
}