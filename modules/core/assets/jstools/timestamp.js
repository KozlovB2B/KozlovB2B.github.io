/**
 * Get current UNIX Timestamp
 * @author Roman Agilov
 * @license MIT license
 **/
var timestamp = function(){
    return Math.round((new Date()).getTime() / 1000);
};