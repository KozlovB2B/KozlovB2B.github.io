/**
 *
 * @constructor
 */
var ParentFrameMessenger = function () {
    //console.log(parent);
    parent.postMessage(JSON.stringify({location:window.location.href}), "*");
};