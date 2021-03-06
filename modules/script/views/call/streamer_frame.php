<?php
/**
 * @var string $account_id
 * @var string $filename
 * @var string $server
 */
?>
<script>
    /*! binary.min.js build:0.2.2, production. Copyright(c) 2012 Eric Zhang <eric@ericzhang.com> MIT Licensed */
    (function (e) {
        function t() {
            this._events = {}
        }

        function i() {
            t.call(this)
        }

        function s(e, t) {
            i.call(this), t = r.extend({readDelay: 0, paused: !1}, t), this._source = e, this._start = 0, this._readChunkSize = t.chunkSize || e.size, this._readDelay = t.readDelay, this.readable = !0, this.paused = t.paused, this._read()
        }

        function o(e, t, n, r) {
            if (!(this instanceof o))return new o(options);
            var s = this;
            i.call(this), this.id = t, this._socket = e, this.writable = !0, this.readable = !0, this.paused = !1, this._closed = !1, this._ended = !1, n && this._write(1, r, this.id)
        }

        function u(e, n) {
            if (!(this instanceof u))return new u(e, n);
            t.call(this);
            var i = this;
            this._options = r.extend({chunkSize: 40960}, n), this.streams = {}, typeof e == "string" ? (this._nextId = 0, this._socket = new WebSocket(e)) : (this._nextId = 1, this._socket = e), this._socket.binaryType = "arraybuffer", this._socket.addEventListener("open", function () {
                i.emit("open")
            }), this._socket.addEventListener("error", function (e) {
                var t = Object.keys(i.streams);
                for (var n = 0, r = t.length; n < r; n++)i.streams[t[n]]._onError(e);
                i.emit("error", e)
            }), this._socket.addEventListener("close", function (e, t) {
                var n = Object.keys(i.streams);
                for (var r = 0, s = n.length; r < s; r++)i.streams[n[r]]._onClose();
                i.emit("close", e, t)
            }), this._socket.addEventListener("message", function (e, t) {
                r.setZeroTimeout(function () {
                    e = e.data;
                    try {
                        e = r.unpack(e)
                    } catch (t) {
                        return i.emit("error", new Error("Received unparsable message: " + t))
                    }
                    if (!(e instanceof Array))return i.emit("error", new Error("Received non-array message"));
                    if (e.length != 3)return i.emit("error", new Error("Received message with wrong part count: " + e.length));
                    if ("number" != typeof e[0])return i.emit("error", new Error("Received message with non-number type: " + e[0]));
                    switch (e[0]) {
                        case 0:
                            break;
                        case 1:
                            var n = e[1], s = e[2], o = i._receiveStream(s);
                            i.emit("stream", o, n);
                            break;
                        case 2:
                            var u = e[1], s = e[2], o = i.streams[s];
                            o ? o._onData(u) : i.emit("error", new Error("Received `data` message for unknown stream: " + s));
                            break;
                        case 3:
                            var s = e[2], o = i.streams[s];
                            o ? o._onPause() : i.emit("error", new Error("Received `pause` message for unknown stream: " + s));
                            break;
                        case 4:
                            var s = e[2], o = i.streams[s];
                            o ? o._onResume() : i.emit("error", new Error("Received `resume` message for unknown stream: " + s));
                            break;
                        case 5:
                            var s = e[2], o = i.streams[s];
                            o ? o._onEnd() : i.emit("error", new Error("Received `end` message for unknown stream: " + s));
                            break;
                        case 6:
                            var s = e[2], o = i.streams[s];
                            o ? o._onClose() : i.emit("error", new Error("Received `close` message for unknown stream: " + s));
                            break;
                        default:
                            i.emit("error", new Error("Unrecognized message type received: " + e[0]))
                    }
                })
            })
        }

        (function a(e, t, n) {
            function r(s, o) {
                if (!t[s]) {
                    if (!e[s]) {
                        var u = typeof require == "function" && require;
                        if (!o && u)return u(s, !0);
                        if (i)return i(s, !0);
                        var f = new Error("Cannot find module '" + s + "'");
                        throw f.code = "MODULE_NOT_FOUND", f
                    }
                    var l = t[s] = {exports: {}};
                    e[s][0].call(l.exports, function (t) {
                        var n = e[s][1][t];
                        return r(n ? n : t)
                    }, l, l.exports, a, e, t, n)
                }
                return t[s].exports
            }

            var i = typeof require == "function" && require;
            for (var s = 0; s < n.length; s++)r(n[s]);
            return r
        })({
            1: [function (e, t, n) {
                function o(e) {
                    this.index = 0, this.dataBuffer = e, this.dataView = new Uint8Array(this.dataBuffer), this.length = this.dataBuffer.byteLength
                }

                function u() {
                    this.bufferBuilder = new r
                }

                function a(e) {
                    var t = e.charCodeAt(0);
                    return t <= 2047 ? "00" : t <= 65535 ? "000" : t <= 2097151 ? "0000" : t <= 67108863 ? "00000" : "000000"
                }

                function f(e) {
                    return e.length > 600 ? (new Blob([e])).size : e.replace(/[^\u0000-\u007F]/g, a).length
                }

                var r = e("./bufferbuilder").BufferBuilder, i = e("./bufferbuilder").binaryFeatures, s = {
                    unpack: function (e) {
                        var t = new o(e);
                        return t.unpack()
                    }, pack: function (e) {
                        var t = new u;
                        t.pack(e);
                        var n = t.getBuffer();
                        return n
                    }
                };
                t.exports = s, o.prototype.unpack = function () {
                    var e = this.unpack_uint8();
                    if (e < 128) {
                        var t = e;
                        return t
                    }
                    if ((e ^ 224) < 32) {
                        var n = (e ^ 224) - 32;
                        return n
                    }
                    var r;
                    if ((r = e ^ 160) <= 15)return this.unpack_raw(r);
                    if ((r = e ^ 176) <= 15)return this.unpack_string(r);
                    if ((r = e ^ 144) <= 15)return this.unpack_array(r);
                    if ((r = e ^ 128) <= 15)return this.unpack_map(r);
                    switch (e) {
                        case 192:
                            return null;
                        case 193:
                            return undefined;
                        case 194:
                            return !1;
                        case 195:
                            return !0;
                        case 202:
                            return this.unpack_float();
                        case 203:
                            return this.unpack_double();
                        case 204:
                            return this.unpack_uint8();
                        case 205:
                            return this.unpack_uint16();
                        case 206:
                            return this.unpack_uint32();
                        case 207:
                            return this.unpack_uint64();
                        case 208:
                            return this.unpack_int8();
                        case 209:
                            return this.unpack_int16();
                        case 210:
                            return this.unpack_int32();
                        case 211:
                            return this.unpack_int64();
                        case 212:
                            return undefined;
                        case 213:
                            return undefined;
                        case 214:
                            return undefined;
                        case 215:
                            return undefined;
                        case 216:
                            return r = this.unpack_uint16(), this.unpack_string(r);
                        case 217:
                            return r = this.unpack_uint32(), this.unpack_string(r);
                        case 218:
                            return r = this.unpack_uint16(), this.unpack_raw(r);
                        case 219:
                            return r = this.unpack_uint32(), this.unpack_raw(r);
                        case 220:
                            return r = this.unpack_uint16(), this.unpack_array(r);
                        case 221:
                            return r = this.unpack_uint32(), this.unpack_array(r);
                        case 222:
                            return r = this.unpack_uint16(), this.unpack_map(r);
                        case 223:
                            return r = this.unpack_uint32(), this.unpack_map(r)
                    }
                }, o.prototype.unpack_uint8 = function () {
                    var e = this.dataView[this.index] & 255;
                    return this.index++, e
                }, o.prototype.unpack_uint16 = function () {
                    var e = this.read(2), t = (e[0] & 255) * 256 + (e[1] & 255);
                    return this.index += 2, t
                }, o.prototype.unpack_uint32 = function () {
                    var e = this.read(4), t = ((e[0] * 256 + e[1]) * 256 + e[2]) * 256 + e[3];
                    return this.index += 4, t
                }, o.prototype.unpack_uint64 = function () {
                    var e = this.read(8), t = ((((((e[0] * 256 + e[1]) * 256 + e[2]) * 256 + e[3]) * 256 + e[4]) * 256 + e[5]) * 256 + e[6]) * 256 + e[7];
                    return this.index += 8, t
                }, o.prototype.unpack_int8 = function () {
                    var e = this.unpack_uint8();
                    return e < 128 ? e : e - 256
                }, o.prototype.unpack_int16 = function () {
                    var e = this.unpack_uint16();
                    return e < 32768 ? e : e - 65536
                }, o.prototype.unpack_int32 = function () {
                    var e = this.unpack_uint32();
                    return e < Math.pow(2, 31) ? e : e - Math.pow(2, 32)
                }, o.prototype.unpack_int64 = function () {
                    var e = this.unpack_uint64();
                    return e < Math.pow(2, 63) ? e : e - Math.pow(2, 64)
                }, o.prototype.unpack_raw = function (e) {
                    if (this.length < this.index + e)throw new Error("BinaryPackFailure: index is out of range " + this.index + " " + e + " " + this.length);
                    var t = this.dataBuffer.slice(this.index, this.index + e);
                    return this.index += e, t
                }, o.prototype.unpack_string = function (e) {
                    var t = this.read(e), n = 0, r = "", i, s;
                    while (n < e)i = t[n], i < 128 ? (r += String.fromCharCode(i), n++) : (i ^ 192) < 32 ? (s = (i ^ 192) << 6 | t[n + 1] & 63, r += String.fromCharCode(s), n += 2) : (s = (i & 15) << 12 | (t[n + 1] & 63) << 6 | t[n + 2] & 63, r += String.fromCharCode(s), n += 3);
                    return this.index += e, r
                }, o.prototype.unpack_array = function (e) {
                    var t = new Array(e);
                    for (var n = 0; n < e; n++)t[n] = this.unpack();
                    return t
                }, o.prototype.unpack_map = function (e) {
                    var t = {};
                    for (var n = 0; n < e; n++) {
                        var r = this.unpack(), i = this.unpack();
                        t[r] = i
                    }
                    return t
                }, o.prototype.unpack_float = function () {
                    var e = this.unpack_uint32(), t = e >> 31, n = (e >> 23 & 255) - 127, r = e & 8388607 | 8388608;
                    return (t == 0 ? 1 : -1) * r * Math.pow(2, n - 23)
                }, o.prototype.unpack_double = function () {
                    var e = this.unpack_uint32(), t = this.unpack_uint32(), n = e >> 31, r = (e >> 20 & 2047) - 1023, i = e & 1048575 | 1048576, s = i * Math.pow(2, r - 20) + t * Math.pow(2, r - 52);
                    return (n == 0 ? 1 : -1) * s
                }, o.prototype.read = function (e) {
                    var t = this.index;
                    if (t + e <= this.length)return this.dataView.subarray(t, t + e);
                    throw new Error("BinaryPackFailure: read index out of range")
                }, u.prototype.getBuffer = function () {
                    return this.bufferBuilder.getBuffer()
                }, u.prototype.pack = function (e) {
                    var t = typeof e;
                    if (t == "string")this.pack_string(e); else if (t == "number")Math.floor(e) === e ? this.pack_integer(e) : this.pack_double(e); else if (t == "boolean")e === !0 ? this.bufferBuilder.append(195) : e === !1 && this.bufferBuilder.append(194); else if (t == "undefined")this.bufferBuilder.append(192); else {
                        if (t != "object")throw new Error('Type "' + t + '" not yet supported');
                        if (e === null)this.bufferBuilder.append(192); else {
                            var n = e.constructor;
                            if (n == Array)this.pack_array(e); else if (n == Blob || n == File)this.pack_bin(e); else if (n == ArrayBuffer)i.useArrayBufferView ? this.pack_bin(new Uint8Array(e)) : this.pack_bin(e); else if ("BYTES_PER_ELEMENT"in e)i.useArrayBufferView ? this.pack_bin(new Uint8Array(e.buffer)) : this.pack_bin(e.buffer); else if (n == Object)this.pack_object(e); else if (n == Date)this.pack_string(e.toString()); else {
                                if (typeof e.toBinaryPack != "function")throw new Error('Type "' + n.toString() + '" not yet supported');
                                this.bufferBuilder.append(e.toBinaryPack())
                            }
                        }
                    }
                    this.bufferBuilder.flush()
                }, u.prototype.pack_bin = function (e) {
                    var t = e.length || e.byteLength || e.size;
                    if (t <= 15)this.pack_uint8(160 + t); else if (t <= 65535)this.bufferBuilder.append(218), this.pack_uint16(t); else {
                        if (!(t <= 4294967295))throw new Error("Invalid length");
                        this.bufferBuilder.append(219), this.pack_uint32(t)
                    }
                    this.bufferBuilder.append(e)
                }, u.prototype.pack_string = function (e) {
                    var t = f(e);
                    if (t <= 15)this.pack_uint8(176 + t); else if (t <= 65535)this.bufferBuilder.append(216), this.pack_uint16(t); else {
                        if (!(t <= 4294967295))throw new Error("Invalid length");
                        this.bufferBuilder.append(217), this.pack_uint32(t)
                    }
                    this.bufferBuilder.append(e)
                }, u.prototype.pack_array = function (e) {
                    var t = e.length;
                    if (t <= 15)this.pack_uint8(144 + t); else if (t <= 65535)this.bufferBuilder.append(220), this.pack_uint16(t); else {
                        if (!(t <= 4294967295))throw new Error("Invalid length");
                        this.bufferBuilder.append(221), this.pack_uint32(t)
                    }
                    for (var n = 0; n < t; n++)this.pack(e[n])
                }, u.prototype.pack_integer = function (e) {
                    if (-32 <= e && e <= 127)this.bufferBuilder.append(e & 255); else if (0 <= e && e <= 255)this.bufferBuilder.append(204), this.pack_uint8(e); else if (-128 <= e && e <= 127)this.bufferBuilder.append(208), this.pack_int8(e); else if (0 <= e && e <= 65535)this.bufferBuilder.append(205), this.pack_uint16(e); else if (-32768 <= e && e <= 32767)this.bufferBuilder.append(209), this.pack_int16(e); else if (0 <= e && e <= 4294967295)this.bufferBuilder.append(206), this.pack_uint32(e); else if (-2147483648 <= e && e <= 2147483647)this.bufferBuilder.append(210), this.pack_int32(e); else if (-0x8000000000000000 <= e && e <= 0x8000000000000000)this.bufferBuilder.append(211), this.pack_int64(e); else {
                        if (!(0 <= e && e <= 0x10000000000000000))throw new Error("Invalid integer");
                        this.bufferBuilder.append(207), this.pack_uint64(e)
                    }
                }, u.prototype.pack_double = function (e) {
                    var t = 0;
                    e < 0 && (t = 1, e = -e);
                    var n = Math.floor(Math.log(e) / Math.LN2), r = e / Math.pow(2, n) - 1, i = Math.floor(r * Math.pow(2, 52)), s = Math.pow(2, 32), o = t << 31 | n + 1023 << 20 | i / s & 1048575, u = i % s;
                    this.bufferBuilder.append(203), this.pack_int32(o), this.pack_int32(u)
                }, u.prototype.pack_object = function (e) {
                    var t = Object.keys(e), n = t.length;
                    if (n <= 15)this.pack_uint8(128 + n); else if (n <= 65535)this.bufferBuilder.append(222), this.pack_uint16(n); else {
                        if (!(n <= 4294967295))throw new Error("Invalid length");
                        this.bufferBuilder.append(223), this.pack_uint32(n)
                    }
                    for (var r in e)e.hasOwnProperty(r) && (this.pack(r), this.pack(e[r]))
                }, u.prototype.pack_uint8 = function (e) {
                    this.bufferBuilder.append(e)
                }, u.prototype.pack_uint16 = function (e) {
                    this.bufferBuilder.append(e >> 8), this.bufferBuilder.append(e & 255)
                }, u.prototype.pack_uint32 = function (e) {
                    var t = e & 4294967295;
                    this.bufferBuilder.append((t & 4278190080) >>> 24), this.bufferBuilder.append((t & 16711680) >>> 16), this.bufferBuilder.append((t & 65280) >>> 8), this.bufferBuilder.append(t & 255)
                }, u.prototype.pack_uint64 = function (e) {
                    var t = e / Math.pow(2, 32), n = e % Math.pow(2, 32);
                    this.bufferBuilder.append((t & 4278190080) >>> 24), this.bufferBuilder.append((t & 16711680) >>> 16), this.bufferBuilder.append((t & 65280) >>> 8), this.bufferBuilder.append(t & 255), this.bufferBuilder.append((n & 4278190080) >>> 24), this.bufferBuilder.append((n & 16711680) >>> 16), this.bufferBuilder.append((n & 65280) >>> 8), this.bufferBuilder.append(n & 255)
                }, u.prototype.pack_int8 = function (e) {
                    this.bufferBuilder.append(e & 255)
                }, u.prototype.pack_int16 = function (e) {
                    this.bufferBuilder.append((e & 65280) >> 8), this.bufferBuilder.append(e & 255)
                }, u.prototype.pack_int32 = function (e) {
                    this.bufferBuilder.append(e >>> 24 & 255), this.bufferBuilder.append((e & 16711680) >>> 16), this.bufferBuilder.append((e & 65280) >>> 8), this.bufferBuilder.append(e & 255)
                }, u.prototype.pack_int64 = function (e) {
                    var t = Math.floor(e / Math.pow(2, 32)), n = e % Math.pow(2, 32);
                    this.bufferBuilder.append((t & 4278190080) >>> 24), this.bufferBuilder.append((t & 16711680) >>> 16), this.bufferBuilder.append((t & 65280) >>> 8), this.bufferBuilder.append(t & 255), this.bufferBuilder.append((n & 4278190080) >>> 24), this.bufferBuilder.append((n & 16711680) >>> 16), this.bufferBuilder.append((n & 65280) >>> 8), this.bufferBuilder.append(n & 255)
                }
            }, {"./bufferbuilder": 2}], 2: [function (e, t, n) {
                function s() {
                    this._pieces = [], this._parts = []
                }

                var r = {};
                r.useBlobBuilder = function () {
                    try {
                        return new Blob([]), !1
                    } catch (e) {
                        return !0
                    }
                }(), r.useArrayBufferView = !r.useBlobBuilder && function () {
                        try {
                            return (new Blob([new Uint8Array([])])).size === 0
                        } catch (e) {
                            return !0
                        }
                    }(), t.exports.binaryFeatures = r;
                var i = t.exports.BlobBuilder;
                typeof window != "undefined" && (i = t.exports.BlobBuilder = window.WebKitBlobBuilder || window.MozBlobBuilder || window.MSBlobBuilder || window.BlobBuilder), s.prototype.append = function (e) {
                    typeof e == "number" ? this._pieces.push(e) : (this.flush(), this._parts.push(e))
                }, s.prototype.flush = function () {
                    if (this._pieces.length > 0) {
                        var e = new Uint8Array(this._pieces);
                        r.useArrayBufferView || (e = e.buffer), this._parts.push(e), this._pieces = []
                    }
                }, s.prototype.getBuffer = function () {
                    this.flush();
                    if (r.useBlobBuilder) {
                        var e = new i;
                        for (var t = 0, n = this._parts.length; t < n; t++)e.append(this._parts[t]);
                        return e.getBlob()
                    }
                    return new Blob(this._parts)
                }, t.exports.BufferBuilder = s
            }, {}], 3: [function (e, t, n) {
                var r = e("./bufferbuilder");
                window.BufferBuilder = r.BufferBuilder, window.binaryFeatures = r.binaryFeatures, window.BlobBuilder = r.BlobBuilder, window.BinaryPack = e("./binarypack")
            }, {"./binarypack": 1, "./bufferbuilder": 2}]
        }, {}, [3]);
        var n = Array.isArray;
        t.prototype.addListener = function (e, t, r, i) {
            if ("function" != typeof t)throw new Error("addListener only takes instances of Function");
            this.emit("newListener", e, typeof t.listener == "function" ? t.listener : t), this._events[e] ? n(this._events[e]) ? this._events[e].push(t) : this._events[e] = [this._events[e], t] : this._events[e] = t
        }, t.prototype.on = t.prototype.addListener, t.prototype.once = function (e, t, n) {
            function i() {
                r.removeListener(e, i), t.apply(this, arguments)
            }

            if ("function" != typeof t)throw new Error(".once only takes instances of Function");
            var r = this;
            return i.listener = t, r.on(e, i), this
        }, t.prototype.removeListener = function (e, t, r) {
            if ("function" != typeof t)throw new Error("removeListener only takes instances of Function");
            if (!this._events[e])return this;
            var i = this._events[e];
            if (n(i)) {
                var s = -1;
                for (var o = 0, u = i.length; o < u; o++)if (i[o] === t || i[o].listener && i[o].listener === t) {
                    s = o;
                    break
                }
                if (s < 0)return this;
                i.splice(s, 1), i.length == 0 && delete this._events[e]
            } else(i === t || i.listener && i.listener === t) && delete this._events[e];
            return this
        }, t.prototype.off = t.prototype.removeListener, t.prototype.removeAllListeners = function (e) {
            return arguments.length === 0 ? (this._events = {}, this) : (e && this._events && this._events[e] && (this._events[e] = null), this)
        }, t.prototype.listeners = function (e) {
            return this._events[e] || (this._events[e] = []), n(this._events[e]) || (this._events[e] = [this._events[e]]), this._events[e]
        }, t.prototype.emit = function (e) {
            var e = arguments[0], t = this._events[e];
            if (!t)return !1;
            if (typeof t == "function") {
                switch (arguments.length) {
                    case 1:
                        t.call(this);
                        break;
                    case 2:
                        t.call(this, arguments[1]);
                        break;
                    case 3:
                        t.call(this, arguments[1], arguments[2]);
                        break;
                    default:
                        var r = arguments.length, i = new Array(r - 1);
                        for (var s = 1; s < r; s++)i[s - 1] = arguments[s];
                        t.apply(this, i)
                }
                return !0
            }
            if (n(t)) {
                var r = arguments.length, i = new Array(r - 1);
                for (var s = 1; s < r; s++)i[s - 1] = arguments[s];
                var o = t.slice();
                for (var s = 0, r = o.length; s < r; s++)o[s].apply(this, i);
                return !0
            }
            return !1
        };
        var r = {
            inherits: function (e, t) {
                e.super_ = t, e.prototype = Object.create(t.prototype, {constructor: {value: e, enumerable: !1, writable: !0, configurable: !0}})
            }, extend: function (e, t) {
                for (var n in t)t.hasOwnProperty(n) && (e[n] = t[n]);
                return e
            }, pack: BinaryPack.pack, unpack: BinaryPack.unpack, setZeroTimeout: function (e) {
                function r(r) {
                    t.push(r), e.postMessage(n, "*")
                }

                function i(r) {
                    r.source == e && r.data == n && (r.stopPropagation && r.stopPropagation(), t.length && t.shift()())
                }

                var t = [], n = "zero-timeout-message";
                return e.addEventListener ? e.addEventListener("message", i, !0) : e.attachEvent && e.attachEvent("onmessage", i), r
            }(this)
        };
        e.util = r, r.inherits(i, t), i.prototype.pipe = function (e, t) {
            function r(t) {
                e.writable && !1 === e.write(t) && n.pause && n.pause()
            }

            function i() {
                n.readable && n.resume && n.resume()
            }

            function o() {
                if (s)return;
                s = !0, e.end()
            }

            function u() {
                if (s)return;
                s = !0, e.destroy()
            }

            function a(e) {
                f();
                if (this.listeners("error").length === 0)throw e
            }

            function f() {
                n.removeListener("data", r), e.removeListener("drain", i), n.removeListener("end", o), n.removeListener("close", u), n.removeListener("error", a), e.removeListener("error", a), n.removeListener("end", f), n.removeListener("close", f), e.removeListener("end", f), e.removeListener("close", f)
            }

            var n = this;
            n.on("data", r), e.on("drain", i), !e._isStdio && (!t || t.end !== !1) && (n.on("end", o), n.on("close", u));
            var s = !1;
            return n.on("error", a), e.on("error", a), n.on("end", f), n.on("close", f), e.on("end", f), e.on("close", f), e.emit("pipe", n), e
        }, e.Stream = i, r.inherits(s, i), s.prototype.pause = function () {
            this.paused = !0
        }, s.prototype.resume = function () {
            this.paused = !1, this._read()
        }, s.prototype.destroy = function () {
            this.readable = !1, clearTimeout(this._timeoutId)
        }, s.prototype._read = function () {
            function t() {
                e._emitReadChunk()
            }

            var e = this, n = this._readDelay;
            n !== 0 ? this._timeoutId = setTimeout(t, n) : r.setZeroTimeout(t)
        }, s.prototype._emitReadChunk = function () {
            if (this.paused || !this.readable)return;
            var e = Math.min(this._source.size - this._start, this._readChunkSize);
            if (e === 0) {
                this.readable = !1, this.emit("end");
                return
            }
            var t = this._start + e, n = (this._source.slice || this._source.webkitSlice || this._source.mozSlice).call(this._source, this._start, t);
            this._start = t, this._read(), this.emit("data", n)
        }, e.BlobReadStream = s, r.inherits(o, i), o.prototype._onDrain = function () {
            this.paused || this.emit("drain")
        }, o.prototype._onClose = function () {
            if (this._closed)return;
            this.readable = !1, this.writable = !1, this._closed = !0, this.emit("close")
        }, o.prototype._onError = function (e) {
            this.readable = !1, this.writable = !1, this.emit("error", e)
        }, o.prototype._onPause = function () {
            this.paused = !0, this.emit("pause")
        }, o.prototype._onResume = function () {
            this.paused = !1, this.emit("resume"), this.emit("drain")
        }, o.prototype._write = function (e, t, n) {
            if (this._socket.readyState !== this._socket.constructor.OPEN)return !1;
            var i = r.pack([e, t, n]);
            return this._socket.send(i) !== !1
        }, o.prototype.write = function (e) {
            if (this.writable) {
                var t = this._write(2, e, this.id);
                return !this.paused && t
            }
            return this.emit("error", new Error("Stream is not writable")), !1
        }, o.prototype.end = function () {
            this._ended = !0, this.readable = !1, this._write(5, null, this.id)
        }, o.prototype.destroy = o.prototype.destroySoon = function () {
            this._onClose(), this._write(6, null, this.id)
        }, o.prototype._onEnd = function () {
            if (this._ended)return;
            this._ended = !0, this.readable = !1, this.emit("end")
        }, o.prototype._onData = function (e) {
            this.emit("data", e)
        }, o.prototype.pause = function () {
            this._onPause(), this._write(3, null, this.id)
        }, o.prototype.resume = function () {
            this._onResume(), this._write(4, null, this.id)
        }, r.inherits(u, t), u.prototype.send = function (e, t) {
            var n = this.createStream(t);
            if (e instanceof i)e.pipe(n); else if (r.isNode === !0)Buffer.isBuffer(e) ? (new BufferReadStream(e, {chunkSize: this._options.chunkSize})).pipe(n) : n.write(e); else if (r.isNode !== !0)if (e.constructor == Blob || e.constructor == File)(new s(e, {chunkSize: this._options.chunkSize})).pipe(n); else if (e.constructor == ArrayBuffer) {
                var o;
                binaryFeatures.useArrayBufferView && (e = new Uint8Array(e));
                if (binaryFeatures.useBlobBuilder) {
                    var u = new BlobBuilder;
                    u.append(e), o = u.getBlob()
                } else o = new Blob([e]);
                (new s(o, {chunkSize: this._options.chunkSize})).pipe(n)
            } else if (typeof e == "object" && "BYTES_PER_ELEMENT"in e) {
                var o;
                binaryFeatures.useArrayBufferView || (e = e.buffer);
                if (binaryFeatures.useBlobBuilder) {
                    var u = new BlobBuilder;
                    u.append(e), o = u.getBlob()
                } else o = new Blob([e]);
                (new s(o, {chunkSize: this._options.chunkSize})).pipe(n)
            } else n.write(e);
            return n
        }, u.prototype._receiveStream = function (e) {
            var t = this, n = new o(this._socket, e, !1);
            return n.on("close", function () {
                delete t.streams[e]
            }), this.streams[e] = n, n
        }, u.prototype.createStream = function (e) {
            if (this._socket.readyState !== WebSocket.OPEN)throw new Error("Client is not yet connected or has closed");
            var t = this, n = this._nextId;
            this._nextId += 2;
            var r = new o(this._socket, n, !0, e);
            return r.on("close", function () {
                delete t.streams[n]
            }), this.streams[n] = r, r
        }, u.prototype.close = u.prototype.destroy = function () {
            this._socket.close()
        }, e.BinaryClient = u
    })(this)
</script>

<script>
    var client = new BinaryClient('wss://<?= $server ?>/recorder?user=<?= $account_id ?>&filename=<?= $filename ?>');

    client.on('stream', function(stream, meta){
        console.log(stream);
        // collect stream data
        var parts = [];
        stream.on('data', function(data){
            console.log(data);
        });
        // when finished, set it as the background image
        stream.on('end', function(){
//            var url = (window.URL || window.webkitURL).createObjectURL(new Blob(parts));
//            document.body.style.backgroundImage = 'url(' + url + ')';
        });
    });
    // listen for a file being chosen
//    fileinput.addEventListener('change', function(event){
//        var file = event.target.files[0];
//        client.send(file);
//    }, false);
    console.log('client init');




</script>