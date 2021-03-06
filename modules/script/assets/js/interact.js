// Interact
!function (t) {
    function e() {
    }

    function i(t) {
        if (!t || "object" != typeof t)return !1;
        var e = S(t) || at;
        return /object|function/.test(typeof e.Element) ? t instanceof e.Element : 1 === t.nodeType && "string" == typeof t.nodeName
    }

    function r(t) {
        return t === at || !(!t || !t.Window) && t instanceof t.Window
    }

    function s(t) {
        return n(t) && void 0 !== typeof t.length && o(t.splice)
    }

    function n(t) {
        return !!t && "object" == typeof t
    }

    function o(t) {
        return "function" == typeof t
    }

    function a(t) {
        return "number" == typeof t
    }

    function h(t) {
        return "boolean" == typeof t
    }

    function p(t) {
        return "string" == typeof t
    }

    function l(t) {
        return p(t) ? (ht.querySelector(t), !0) : !1
    }

    function c(t, e) {
        for (var i in e)t[i] = e[i];
        return t
    }

    function d(t, e) {
        for (var i in e) {
            var r, s = !1;
            for (r in Ht)if (0 === i.indexOf(r) && Ht[r].test(i)) {
                s = !0;
                break
            }
            s || (t[i] = e[i])
        }
        return t
    }

    function u(t, e) {
        t.page = t.page || {}, t.page.x = e.page.x, t.page.y = e.page.y, t.client = t.client || {}, t.client.x = e.client.x, t.client.y = e.client.y, t.timeStamp = e.timeStamp
    }

    function g(t, e, i) {
        t.page.x = i.page.x - e.page.x, t.page.y = i.page.y - e.page.y, t.client.x = i.client.x - e.client.x, t.client.y = i.client.y - e.client.y, t.timeStamp = (new Date).getTime() - e.timeStamp, e = Math.max(t.timeStamp / 1e3, .001), t.page.speed = mt(t.page.x, t.page.y) / e, t.page.vx = t.page.x / e, t.page.vy = t.page.y / e, t.client.speed = mt(t.client.x, t.page.y) / e, t.client.vx = t.client.x / e, t.client.vy = t.client.y / e
    }

    function m(t) {
        return t instanceof at.Event || zt && at.Touch && t instanceof at.Touch
    }

    function v(t, e, i) {
        return i = i || {}, t = t || "page", i.x = e[t + "X"], i.y = e[t + "Y"], i
    }

    function f(t, e) {
        return e = e || {}, Yt && m(t) ? (v("screen", t, e), e.x += at.scrollX, e.y += at.scrollY) : v("page", t, e), e
    }

    function y(t, e) {
        return e = e || {}, Yt && m(t) ? v("screen", t, e) : v("client", t, e), e
    }

    function x(t) {
        return a(t.pointerId) ? t.pointerId : t.identifier
    }

    function E(t) {
        return t instanceof dt ? t.correspondingUseElement : t
    }

    function S(t) {
        return r(t) ? t : (t = t.ownerDocument || t, t.defaultView || t.parentWindow || at)
    }

    function b(t) {
        return (t = t instanceof lt ? t.getBoundingClientRect() : t.getClientRects()[0]) && {left: t.left, right: t.right, top: t.top, bottom: t.bottom, width: t.width || t.right - t.left, height: t.height || t.bottom - t.top}
    }

    function w(t) {
        var e, i = b(t);
        return !It && i && (e = (e = S(t)) || at, t = e.scrollX || e.document.documentElement.scrollLeft, e = e.scrollY || e.document.documentElement.scrollTop, i.left += t, i.right += t, i.top += e, i.bottom += e), i
    }

    function z(t) {
        var e = [];
        return s(t) ? (e[0] = t[0], e[1] = t[1]) : "touchend" === t.type ? 1 === t.touches.length ? (e[0] = t.touches[0], e[1] = t.changedTouches[0]) : 0 === t.touches.length && (e[0] = t.changedTouches[0], e[1] = t.changedTouches[1]) : (e[0] = t.touches[0], e[1] = t.touches[1]), e
    }

    function D(t) {
        for (var e, i = {pageX: 0, pageY: 0, clientX: 0, clientY: 0, screenX: 0, screenY: 0}, r = 0; r < t.length; r++)for (e in i)i[e] += t[r][e];
        for (e in i)i[e] /= t.length;
        return i
    }

    function T(t) {
        if (t.length || t.touches && 1 < t.touches.length) {
            t = z(t);
            var e = Math.min(t[0].pageX, t[1].pageX), i = Math.min(t[0].pageY, t[1].pageY);
            return {x: e, y: i, left: e, top: i, width: Math.max(t[0].pageX, t[1].pageX) - e, height: Math.max(t[0].pageY, t[1].pageY) - i}
        }
    }

    function C(t, e) {
        e = e || bt.deltaSource;
        var i = e + "X", r = e + "Y", s = z(t);
        return mt(s[0][i] - s[1][i], s[0][r] - s[1][r])
    }

    function M(t, e, i) {
        i = i || bt.deltaSource;
        var r = i + "X";
        return i += "Y", t = z(t), r = 180 * Math.atan((t[0][i] - t[1][i]) / (t[0][r] - t[1][r])) / Math.PI, a(e) && (e = (r - e) % 360, e > 315 ? r -= 360 + r / 360 | 0 : e > 135 ? r -= 180 + r / 360 | 0 : -315 > e ? r += 360 + r / 360 | 0 : -135 > e && (r += 180 + r / 360 | 0)), r
    }

    function P(t, e) {
        var r = t ? t.options.origin : bt.origin;
        return "parent" === r ? r = _(e) : "self" === r ? r = t.getRect(e) : l(r) && (r = O(e, r) || {x: 0, y: 0}), o(r) && (r = r(t && e)), i(r) && (r = w(r)), r.x = "x"in r ? r.x : r.left, r.y = "y"in r ? r.y : r.top, r
    }

    function A(t, e, i, r) {
        var s = 1 - t;
        return s * s * e + 2 * s * t * i + t * t * r
    }

    function k(t, e) {
        for (; e;) {
            if (e === t)return !0;
            e = e.parentNode
        }
        return !1
    }

    function O(t, e) {
        for (var r = _(t); i(r);) {
            if (rt(r, e))return r;
            r = _(r)
        }
        return null
    }

    function _(t) {
        if ((t = t.parentNode) && t instanceof pt)for (; (t = t.host) && t && t instanceof pt;);
        return t
    }

    function X(t, e) {
        return t._context === e.ownerDocument || k(t._context, e)
    }

    function Y(t, e, r) {
        return (t = t.options.ignoreFrom) && i(r) ? p(t) ? st(r, t, e) : i(t) ? k(t, r) : !1 : !1
    }

    function I(t, e, r) {
        return (t = t.options.allowFrom) ? i(r) ? p(t) ? st(r, t, e) : i(t) ? k(t, r) : !1 : !1 : !0
    }

    function R(t, e) {
        if (!e)return !1;
        var i = e.options.drag.axis;
        return "xy" === t || "xy" === i || i === t
    }

    function F(t, e) {
        var i = t.options;
        return /^resize/.test(e) && (e = "resize"), i[e].snap && i[e].snap.enabled
    }

    function N(t, e) {
        var i = t.options;
        return /^resize/.test(e) && (e = "resize"), i[e].restrict && i[e].restrict.enabled
    }

    function q(t, e, i) {
        for (var r = t.options, s = r[i.name].max, r = r[i.name].maxPerElement, n = 0, o = 0, a = 0, h = 0, p = xt.length; p > h; h++) {
            var l = xt[h], c = l.prepared.name;
            if (l.interacting() && (n++, n >= Pt || l.target === t && (o += c === i.name | 0, o >= s || l.element === e && (a++, c !== i.name || a >= r))))return !1
        }
        return Pt > 0
    }

    function H() {
        if (this.prevDropElement = this.prevDropTarget = this.dropElement = this.dropTarget = this.element = this.target = null, this.prepared = {name: null, axis: null, edges: null}, this.matches = [], this.matchElements = [], this.inertiaStatus = {
                active: !1,
                smoothEnd: !1,
                ending: !1,
                startEvent: null,
                upCoords: {},
                xe: 0,
                ye: 0,
                sx: 0,
                sy: 0,
                t0: 0,
                vx0: 0,
                vys: 0,
                duration: 0,
                resumeDx: 0,
                resumeDy: 0,
                lambda_v0: 0,
                one_ve_v0: 0,
                i: null
            }, o(Function.prototype.bind))this.boundInertiaFrame = this.inertiaFrame.bind(this), this.boundSmoothEndFrame = this.smoothEndFrame.bind(this); else {
            var t = this;
            this.boundInertiaFrame = function () {
                return t.inertiaFrame()
            }, this.boundSmoothEndFrame = function () {
                return t.smoothEndFrame()
            }
        }
        this.activeDrops = {dropzones: [], elements: [], rects: []}, this.pointers = [], this.pointerIds = [], this.downTargets = [], this.downTimes = [], this.holdTimers = [], this.prevCoords = {page: {x: 0, y: 0}, client: {x: 0, y: 0}, timeStamp: 0}, this.curCoords = {
            page: {x: 0, y: 0},
            client: {x: 0, y: 0},
            timeStamp: 0
        }, this.startCoords = {page: {x: 0, y: 0}, client: {x: 0, y: 0}, timeStamp: 0}, this.pointerDelta = {
            page: {x: 0, y: 0, vx: 0, vy: 0, speed: 0},
            client: {x: 0, y: 0, vx: 0, vy: 0, speed: 0},
            timeStamp: 0
        }, this.downEvent = null, this.downPointer = {}, this.prevEvent = this._curEventTarget = this._eventTarget = null, this.tapTime = 0, this.prevTap = null, this.startOffset = {left: 0, right: 0, top: 0, bottom: 0}, this.restrictOffset = {
            left: 0,
            right: 0,
            top: 0,
            bottom: 0
        }, this.snapOffsets = [], this.gesture = {start: {x: 0, y: 0}, startDistance: 0, prevDistance: 0, distance: 0, scale: 1, startAngle: 0, prevAngle: 0}, this.snapStatus = {
            x: 0,
            y: 0,
            dx: 0,
            dy: 0,
            realX: 0,
            realY: 0,
            snappedX: 0,
            snappedY: 0,
            targets: [],
            locked: !1,
            changed: !1
        }, this.restrictStatus = {
            dx: 0,
            dy: 0,
            restrictedX: 0,
            restrictedY: 0,
            snap: null,
            restricted: !1,
            changed: !1
        }, this.restrictStatus.snap = this.snapStatus, this.resizing = this.dragging = this.gesturing = this.pointerWasMoved = this.pointerIsDown = !1, this.resizeAxes = "xy", this.mouse = !1, xt.push(this)
    }

    function W(t, e, i) {
        var r, s = 0, n = xt.length, o = /mouse/i.test(t.pointerType || e) || 4 === t.pointerType;
        if (t = x(t), /down|start/i.test(e))for (s = 0; n > s; s++) {
            r = xt[s];
            var a = i;
            if (r.inertiaStatus.active && r.target.options[r.prepared.name].inertia.allowResume && r.mouse === o)for (; a;) {
                if (a === r.element)return r;
                a = _(a)
            }
        }
        if (o || !zt && !Dt) {
            for (s = 0; n > s; s++)if (xt[s].mouse && !xt[s].inertiaStatus.active)return xt[s];
            for (s = 0; n > s; s++)if (xt[s].mouse && (!/down/.test(e) || !xt[s].inertiaStatus.active))return r;
            return r = new H, r.mouse = !0, r
        }
        for (s = 0; n > s; s++)if (-1 !== it(xt[s].pointerIds, t))return xt[s];
        if (/up|end|out/i.test(e))return null;
        for (s = 0; n > s; s++)if (r = xt[s], !(r.prepared.name && !r.target.options.gesture.enabled || r.interacting() || !o && r.mouse))return r;
        return new H
    }

    function U(t) {
        return function (e) {
            var i, r, s = E(e.path ? e.path[0] : e.target), n = E(e.currentTarget);
            if (zt && /touch/.test(e.type))for (Mt = (new Date).getTime(), r = 0; r < e.changedTouches.length; r++) {
                var o = e.changedTouches[r];
                (i = W(o, e.type, s)) && (i._updateEventTargets(s, n), i[t](o, e, s, n))
            } else {
                if (!Dt && /mouse/.test(e.type)) {
                    for (r = 0; r < xt.length; r++)if (!xt[r].mouse && xt[r].pointerIsDown)return;
                    if (500 > (new Date).getTime() - Mt)return
                }
                (i = W(e, e.type, s)) && (i._updateEventTargets(s, n), i[t](e, e, s, n))
            }
        }
    }

    function V(t, e, i, r, s, n) {
        var o, a, h = t.target, p = t.snapStatus, l = t.restrictStatus, d = t.pointers, u = (h && h.options || bt).deltaSource, g = u + "X", m = u + "Y", v = h ? h.options : bt, f = P(h, s), y = "start" === r, x = "end" === r;
        o = y ? t.startCoords : t.curCoords, s = s || t.element, a = c({}, o.page), o = c({}, o.client), a.x -= f.x, a.y -= f.y, o.x -= f.x, o.y -= f.y;
        var E = v[i].snap && v[i].snap.relativePoints;
        !F(h, i) || y && E && E.length || (this.snap = {
            range: p.range,
            locked: p.locked,
            x: p.snappedX,
            y: p.snappedY,
            realX: p.realX,
            realY: p.realY,
            dx: p.dx,
            dy: p.dy
        }, p.locked && (a.x += p.dx, a.y += p.dy, o.x += p.dx, o.y += p.dy)), !N(h, i) || y && v[i].restrict.elementRect || !l.restricted || (a.x += l.dx, a.y += l.dy, o.x += l.dx, o.y += l.dy, this.restrict = {
            dx: l.dx,
            dy: l.dy
        }), this.pageX = a.x, this.pageY = a.y, this.clientX = o.x, this.clientY = o.y, this.x0 = t.startCoords.page.x - f.x, this.y0 = t.startCoords.page.y - f.y, this.clientX0 = t.startCoords.client.x - f.x, this.clientY0 = t.startCoords.client.y - f.y, this.ctrlKey = e.ctrlKey, this.altKey = e.altKey, this.shiftKey = e.shiftKey, this.metaKey = e.metaKey, this.button = e.button, this.buttons = e.buttons, this.target = s, this.t0 = t.downTimes[0], this.type = i + (r || ""), this.interaction = t, this.interactable = h, s = t.inertiaStatus, s.active && (this.detail = "inertia"), n && (this.relatedTarget = n), x ? "client" === u ? (this.dx = o.x - t.startCoords.client.x, this.dy = o.y - t.startCoords.client.y) : (this.dx = a.x - t.startCoords.page.x, this.dy = a.y - t.startCoords.page.y) : y ? this.dy = this.dx = 0 : "inertiastart" === r ? (this.dx = t.prevEvent.dx, this.dy = t.prevEvent.dy) : "client" === u ? (this.dx = o.x - t.prevEvent.clientX, this.dy = o.y - t.prevEvent.clientY) : (this.dx = a.x - t.prevEvent.pageX, this.dy = a.y - t.prevEvent.pageY), t.prevEvent && "inertia" === t.prevEvent.detail && !s.active && v[i].inertia && v[i].inertia.zeroResumeDelta && (s.resumeDx += this.dx, s.resumeDy += this.dy, this.dx = this.dy = 0), "resize" === i && t.resizeAxes ? v.resize.square ? ("y" === t.resizeAxes ? this.dx = this.dy : this.dy = this.dx, this.axes = "xy") : (this.axes = t.resizeAxes, "x" === t.resizeAxes ? this.dy = 0 : "y" === t.resizeAxes && (this.dx = 0)) : "gesture" === i && (this.touches = [d[0], d[1]], y ? (this.distance = C(d, u), this.box = T(d), this.scale = 1, this.ds = 0, this.angle = M(d, void 0, u), this.da = 0) : x || e instanceof V ? (this.distance = t.prevEvent.distance, this.box = t.prevEvent.box, this.scale = t.prevEvent.scale, this.ds = this.scale - 1, this.angle = t.prevEvent.angle, this.da = this.angle - t.gesture.startAngle) : (this.distance = C(d, u), this.box = T(d), this.scale = this.distance / t.gesture.startDistance, this.angle = M(d, t.gesture.prevAngle, u), this.ds = this.scale - t.gesture.prevScale, this.da = this.angle - t.gesture.prevAngle)), y ? (this.timeStamp = t.downTimes[0], this.velocityY = this.velocityX = this.speed = this.duration = this.dt = 0) : "inertiastart" === r ? (this.timeStamp = t.prevEvent.timeStamp, this.dt = t.prevEvent.dt, this.duration = t.prevEvent.duration, this.speed = t.prevEvent.speed, this.velocityX = t.prevEvent.velocityX, this.velocityY = t.prevEvent.velocityY) : (this.timeStamp = (new Date).getTime(), this.dt = this.timeStamp - t.prevEvent.timeStamp, this.duration = this.timeStamp - t.downTimes[0], e instanceof V ? (e = this[g] - t.prevEvent[g], m = this[m] - t.prevEvent[m], i = this.dt / 1e3, this.speed = mt(e, m) / i, this.velocityX = e / i, this.velocityY = m / i) : (this.speed = t.pointerDelta[u].speed, this.velocityX = t.pointerDelta[u].vx, this.velocityY = t.pointerDelta[u].vy)), (x || "inertiastart" === r) && 600 < t.prevEvent.speed && 150 > this.timeStamp - t.prevEvent.timeStamp && (r = 180 * Math.atan2(t.prevEvent.velocityY, t.prevEvent.velocityX) / Math.PI, 0 > r && (r += 360), x = r >= 112.5 && 247.5 > r, m = r >= 202.5 && 337.5 > r, this.swipe = {
            up: m,
            down: !m && r >= 22.5 && 157.5 > r,
            left: x,
            right: !x && (r >= 292.5 || 67.5 > r),
            angle: r,
            speed: t.prevEvent.speed,
            velocity: {x: t.prevEvent.velocityX, y: t.prevEvent.velocityY}
        })
    }

    function $() {
        this.originalEvent.preventDefault()
    }

    function G(t) {
        var e = "";
        if ("drag" === t.name && (e = At.drag), "resize" === t.name)if (t.axis)e = At[t.name + t.axis]; else if (t.edges) {
            for (var e = "resize", i = ["top", "bottom", "left", "right"], r = 0; 4 > r; r++)t.edges[i[r]] && (e += i[r]);
            e = At[e]
        }
        return e
    }

    function L(t, e, r) {
        t = this.getRect(r);
        var s, o = !1, h = null, p = null, l = c({}, e.curCoords.page), h = this.options;
        if (!t)return null;
        if (kt.resize && h.resize.enabled)if (o = h.resize, s = {left: !1, right: !1, top: !1, bottom: !1}, n(o.edges)) {
            for (var d in s) {
                var u, g = s, m = d;
                t:{
                    u = d;
                    var v = o.edges[d], f = l, y = e._eventTarget, x = r, E = t, S = o.margin || Tt;
                    if (v) {
                        if (!0 === v) {
                            var b = a(E.width) ? E.width : E.right - E.left, w = a(E.height) ? E.height : E.bottom - E.top;
                            if (0 > b && ("left" === u ? u = "right" : "right" === u && (u = "left")), 0 > w && ("top" === u ? u = "bottom" : "bottom" === u && (u = "top")), "left" === u) {
                                u = f.x < (b >= 0 ? E.left : E.right) + S;
                                break t
                            }
                            if ("top" === u) {
                                u = f.y < (w >= 0 ? E.top : E.bottom) + S;
                                break t
                            }
                            if ("right" === u) {
                                u = f.x > (b >= 0 ? E.right : E.left) - S;
                                break t
                            }
                            if ("bottom" === u) {
                                u = f.y > (w >= 0 ? E.bottom : E.top) - S;
                                break t
                            }
                        }
                        u = i(y) ? i(v) ? v === y : st(y, v, x) : !1
                    } else u = !1
                }
                g[m] = u
            }
            s.left = s.left && !s.right, s.top = s.top && !s.bottom, o = s.left || s.right || s.top || s.bottom
        } else r = "y" !== h.resize.axis && l.x > t.right - Tt, t = "x" !== h.resize.axis && l.y > t.bottom - Tt, o = r || t, p = (r ? "x" : "") + (t ? "y" : "");
        return h = o ? "resize" : kt.drag && h.drag.enabled ? "drag" : null, kt.gesture && 2 <= e.pointerIds.length && !e.dragging && !e.resizing && (h = "gesture"), h ? {name: h, axis: p, edges: s} : null
    }

    function j(t, e) {
        if (!n(t))return null;
        var i = t.name, r = e.options;
        return ("resize" === i && r.resize.enabled || "drag" === i && r.drag.enabled || "gesture" === i && r.gesture.enabled) && kt[i] ? t : null
    }

    function B(t, e) {
        var r = {}, s = St[t.type], n = E(t.path ? t.path[0] : t.target), o = n;
        e = e ? !0 : !1;
        for (var a in t)r[a] = t[a];
        for (r.originalEvent = t, r.preventDefault = $; i(o);) {
            for (a = 0; a < s.selectors.length; a++) {
                var h = s.contexts[a];
                if (rt(o, s.selectors[a]) && k(h, n) && k(h, o)) {
                    h = s.listeners[a], r.currentTarget = o;
                    for (var p = 0; p < h.length; p++)h[p][1] === e && h[p][0](r)
                }
            }
            o = _(o)
        }
    }

    function K(t) {
        return B.call(this, t, !0)
    }

    function J(t, e) {
        return yt.get(t, e) || new Q(t, e)
    }

    function Q(t, e) {
        this._element = t, this._iEvents = this._iEvents || {};
        var r;
        if (l(t)) {
            this.selector = t;
            var s = e && e.context;
            r = s ? S(s) : at, s && (r.Node ? s instanceof r.Node : i(s) || s === r.document) && (this._context = s)
        } else r = S(t), i(t, r) && (gt ? (qt.add(this._element, nt.down, Wt.pointerDown), qt.add(this._element, nt.move, Wt.pointerHover)) : (qt.add(this._element, "mousedown", Wt.pointerDown), qt.add(this._element, "mousemove", Wt.pointerHover), qt.add(this._element, "touchstart", Wt.pointerDown), qt.add(this._element, "touchmove", Wt.pointerHover)));
        this._doc = r.document, -1 === it(ft, this._doc) && et(this._doc), yt.push(this), this.set(e)
    }

    function Z(t, e) {
        var i = !1;
        return function () {
            return i || (at.console.warn(e), i = !0), t.apply(this, arguments)
        }
    }

    function tt(t) {
        for (var e = 0; e < xt.length; e++)xt[e].pointerEnd(t, t)
    }

    function et(t) {
        if (-1 === it(ft, t)) {
            var e, i = t.defaultView || t.parentWindow;
            for (e in St)qt.add(t, e, B), qt.add(t, e, K, !0);
            gt ? (nt = gt === i.MSPointerEvent ? {up: "MSPointerUp", down: "MSPointerDown", over: "mouseover", out: "mouseout", move: "MSPointerMove", cancel: "MSPointerCancel"} : {
                up: "pointerup",
                down: "pointerdown",
                over: "pointerover",
                out: "pointerout",
                move: "pointermove",
                cancel: "pointercancel"
            }, qt.add(t, nt.down, Wt.selectorDown), qt.add(t, nt.move, Wt.pointerMove), qt.add(t, nt.over, Wt.pointerOver), qt.add(t, nt.out, Wt.pointerOut), qt.add(t, nt.up, Wt.pointerUp), qt.add(t, nt.cancel, Wt.pointerCancel), qt.add(t, nt.move, Wt.autoScrollMove)) : (qt.add(t, "mousedown", Wt.selectorDown), qt.add(t, "mousemove", Wt.pointerMove), qt.add(t, "mouseup", Wt.pointerUp), qt.add(t, "mouseover", Wt.pointerOver), qt.add(t, "mouseout", Wt.pointerOut), qt.add(t, "touchstart", Wt.selectorDown), qt.add(t, "touchmove", Wt.pointerMove), qt.add(t, "touchend", Wt.pointerUp), qt.add(t, "touchcancel", Wt.pointerCancel), qt.add(t, "mousemove", Wt.autoScrollMove), qt.add(t, "touchmove", Wt.autoScrollMove)), qt.add(i, "blur", tt);
            try {
                if (i.frameElement) {
                    var r = i.frameElement.ownerDocument, s = r.defaultView;
                    qt.add(r, "mouseup", Wt.pointerEnd), qt.add(r, "touchend", Wt.pointerEnd), qt.add(r, "touchcancel", Wt.pointerEnd), qt.add(r, "pointerup", Wt.pointerEnd), qt.add(r, "MSPointerUp", Wt.pointerEnd), qt.add(s, "blur", tt)
                }
            } catch (n) {
                J.windowParentError = n
            }
            qt.add(t, "dragstart", function (t) {
                for (var e = 0; e < xt.length; e++) {
                    var i = xt[e];
                    if (i.element && (i.element === t.target || k(i.element, t.target))) {
                        i.checkAndPreventDefault(t, i.target, i.element);
                        break
                    }
                }
            }), qt.useAttachEvent && (qt.add(t, "selectstart", function (t) {
                var e = xt[0];
                e.currentAction() && e.checkAndPreventDefault(t)
            }), qt.add(t, "dblclick", U("ie8Dblclick"))), ft.push(t)
        }
    }

    function it(t, e) {
        for (var i = 0, r = t.length; r > i; i++)if (t[i] === e)return i;
        return -1
    }

    function rt(e, i, r) {
        return ot ? ot(e, i, r) : (at !== t && (i = i.replace(/\/deep\//g, " ")), e[Rt](i))
    }

    function st(t, e, r) {
        for (; i(t);) {
            if (rt(t, e))return !0;
            if (t = _(t), t === r)return rt(t, e)
        }
        return !1
    }

    if (t) {
        var nt, ot, at = function () {
            var e = t.document.createTextNode("");
            return e.ownerDocument !== t.document && "function" == typeof t.wrap && t.wrap(e) === e ? t.wrap(t) : t
        }(), ht = at.document, pt = at.DocumentFragment || e, lt = at.SVGElement || e, ct = at.SVGSVGElement || e, dt = at.SVGElementInstance || e, ut = at.HTMLElement || at.Element, gt = at.PointerEvent || at.MSPointerEvent, mt = Math.hypot || function (t, e) {
                return Math.sqrt(t * t + e * e)
            }, vt = {}, ft = [], yt = [], xt = [], Et = !1, St = {}, bt = {
            base: {accept: null, actionChecker: null, styleCursor: !0, preventDefault: "auto", origin: {x: 0, y: 0}, deltaSource: "page", allowFrom: null, ignoreFrom: null, _context: ht, dropChecker: null},
            drag: {enabled: !1, manualStart: !0, max: 1 / 0, maxPerElement: 1, snap: null, restrict: null, inertia: null, autoScroll: null, axis: "xy"},
            drop: {enabled: !1, accept: null, overlap: "pointer"},
            resize: {enabled: !1, manualStart: !1, max: 1 / 0, maxPerElement: 1, snap: null, restrict: null, inertia: null, autoScroll: null, square: !1, preserveAspectRatio: !1, axis: "xy", margin: NaN, edges: null, invert: "none"},
            gesture: {manualStart: !1, enabled: !1, max: 1 / 0, maxPerElement: 1, restrict: null},
            perAction: {
                manualStart: !1,
                max: 1 / 0,
                maxPerElement: 1,
                snap: {enabled: !1, endOnly: !1, range: 1 / 0, targets: null, offsets: null, relativePoints: null},
                restrict: {enabled: !1, endOnly: !1},
                autoScroll: {enabled: !1, container: null, margin: 60, speed: 300},
                inertia: {enabled: !1, resistance: 10, minSpeed: 100, endSpeed: 10, allowResume: !0, zeroResumeDelta: !0, smoothEndDuration: 300}
            },
            _holdDuration: 600
        }, wt = {
            interaction: null, i: null, x: 0, y: 0, scroll: function () {
                var t, e = wt.interaction.target.options[wt.interaction.prepared.name].autoScroll, i = e.container || S(wt.interaction.element), s = (new Date).getTime(), n = (s - wt.prevTimeX) / 1e3, o = (s - wt.prevTimeY) / 1e3;
                e.velocity ? (t = e.velocity.x, e = e.velocity.y) : t = e = e.speed, n *= t, o *= e, (n >= 1 || o >= 1) && (r(i) ? i.scrollBy(wt.x * n, wt.y * o) : i && (i.scrollLeft += wt.x * n, i.scrollTop += wt.y * o), n >= 1 && (wt.prevTimeX = s), o >= 1 && (wt.prevTimeY = s)), wt.isScrolling && (Nt(wt.i), wt.i = Ft(wt.scroll))
            }, isScrolling: !1, prevTimeX: 0, prevTimeY: 0, start: function (t) {
                wt.isScrolling = !0, Nt(wt.i), wt.interaction = t, wt.prevTimeX = (new Date).getTime(), wt.prevTimeY = (new Date).getTime(), wt.i = Ft(wt.scroll)
            }, stop: function () {
                wt.isScrolling = !1, Nt(wt.i)
            }
        }, zt = "ontouchstart"in at || at.DocumentTouch && ht instanceof at.DocumentTouch, Dt = !!gt, Tt = zt || Dt ? 20 : 10, Ct = 1, Mt = 0, Pt = 1 / 0, At = ht.all && !at.atob ? {
            drag: "move",
            resizex: "e-resize",
            resizey: "s-resize",
            resizexy: "se-resize",
            resizetop: "n-resize",
            resizeleft: "w-resize",
            resizebottom: "s-resize",
            resizeright: "e-resize",
            resizetopleft: "se-resize",
            resizebottomright: "se-resize",
            resizetopright: "ne-resize",
            resizebottomleft: "ne-resize",
            gesture: ""
        } : {
            drag: "move",
            resizex: "ew-resize",
            resizey: "ns-resize",
            resizexy: "nwse-resize",
            resizetop: "ns-resize",
            resizeleft: "ew-resize",
            resizebottom: "ns-resize",
            resizeright: "ew-resize",
            resizetopleft: "nwse-resize",
            resizebottomright: "nwse-resize",
            resizetopright: "nesw-resize",
            resizebottomleft: "nesw-resize",
            gesture: ""
        }, kt = {
            drag: !0,
            resize: !0,
            gesture: !0
        }, Ot = "onmousewheel"in ht ? "mousewheel" : "wheel", _t = "dragstart dragmove draginertiastart dragend dragenter dragleave dropactivate dropdeactivate dropmove drop resizestart resizemove resizeinertiastart resizeend gesturestart gesturemove gestureinertiastart gestureend down move up cancel tap doubletap hold".split(" "), Xt = {}, Yt = "Opera" == navigator.appName && zt && navigator.userAgent.match("Presto"), It = /iP(hone|od|ad)/.test(navigator.platform) && /OS 7[^\d]/.test(navigator.appVersion), Rt = "matches"in Element.prototype ? "matches" : "webkitMatchesSelector"in Element.prototype ? "webkitMatchesSelector" : "mozMatchesSelector"in Element.prototype ? "mozMatchesSelector" : "oMatchesSelector"in Element.prototype ? "oMatchesSelector" : "msMatchesSelector", Ft = t.requestAnimationFrame, Nt = t.cancelAnimationFrame, qt = function () {
            function t(e, i, r, n) {
                var c, d, u, g = it(h, e), m = p[g], v = r;
                if (m && m.events)if (s && (d = l[g], u = it(d.supplied, r), v = d.wrapped[u]), "all" === i)for (i in m.events)m.events.hasOwnProperty(i) && t(e, i, "all"); else {
                    if (m.events[i]) {
                        var f = m.events[i].length;
                        if ("all" === r) {
                            for (c = 0; f > c; c++)t(e, i, m.events[i][c], Boolean(n));
                            return
                        }
                        for (c = 0; f > c; c++)if (m.events[i][c] === r) {
                            e[o](a + i, v, n || !1), m.events[i].splice(c, 1), s && d && (d.useCount[u]--, 0 === d.useCount[u] && (d.supplied.splice(u, 1), d.wrapped.splice(u, 1), d.useCount.splice(u, 1)));
                            break
                        }
                        m.events[i] && 0 === m.events[i].length && (m.events[i] = null, m.typeCount--)
                    }
                    m.typeCount || (p.splice(g, 1), h.splice(g, 1), l.splice(g, 1))
                }
            }

            function e() {
                this.returnValue = !1
            }

            function i() {
                this.cancelBubble = !0
            }

            function r() {
                this.immediatePropagationStopped = this.cancelBubble = !0
            }

            var s = "attachEvent"in at && !("addEventListener"in at), n = s ? "attachEvent" : "addEventListener", o = s ? "detachEvent" : "removeEventListener", a = s ? "on" : "", h = [], p = [], l = [];
            return {
                add: function (t, o, c, d) {
                    var u = it(h, t), g = p[u];
                    if (g || (g = {events: {}, typeCount: 0}, u = h.push(t) - 1, p.push(g), l.push(s ? {supplied: [], wrapped: [], useCount: []} : null)), g.events[o] || (g.events[o] = [], g.typeCount++), -1 === it(g.events[o], c)) {
                        if (s) {
                            var u = l[u], m = it(u.supplied, c), v = u.wrapped[m] || function (s) {
                                    s.immediatePropagationStopped || (s.target = s.srcElement, s.currentTarget = t, s.preventDefault = s.preventDefault || e, s.stopPropagation = s.stopPropagation || i, s.stopImmediatePropagation = s.stopImmediatePropagation || r, /mouse|click/.test(s.type) && (s.pageX = s.clientX + S(t).document.documentElement.scrollLeft, s.pageY = s.clientY + S(t).document.documentElement.scrollTop), c(s))
                                };
                            d = t[n](a + o, v, Boolean(d)), -1 === m ? (u.supplied.push(c), u.wrapped.push(v), u.useCount.push(1)) : u.useCount[m]++
                        } else d = t[n](o, c, d || !1);
                        return g.events[o].push(c), d
                    }
                }, remove: t, useAttachEvent: s, _elements: h, _targets: p, _attachedListeners: l
            }
        }(), Ht = {webkit: /(Movement[XY]|Radius[XY]|RotationAngle|Force)$/};
        H.prototype = {
            getPageXY: function (t, e) {
                return f(t, e, this)
            }, getClientXY: function (t, e) {
                return y(t, e, this)
            }, setEventXY: function (t, e) {
                var i = 1 < e.length ? D(e) : e[0];
                f(i, vt, this), t.page.x = vt.x, t.page.y = vt.y, y(i, vt, this), t.client.x = vt.x, t.client.y = vt.y, t.timeStamp = (new Date).getTime()
            }, pointerOver: function (t, e, i) {
                function r(t, e) {
                    t && X(t, i) && !Y(t, i, i) && I(t, i, i) && rt(i, e) && (s.push(t), n.push(i))
                }

                if (!this.prepared.name && this.mouse) {
                    var s = [], n = [], o = this.element;
                    this.addPointer(t), !this.target || !Y(this.target, this.element, i) && I(this.target, this.element, i) || (this.element = this.target = null, this.matches = [], this.matchElements = []);
                    var a = yt.get(i), h = a && !Y(a, i, i) && I(a, i, i) && j(a.getAction(t, e, this, i), a);
                    h && !q(a, i, h) && (h = null), h ? (this.target = a, this.element = i, this.matches = [], this.matchElements = []) : (yt.forEachSelector(r), this.validateSelector(t, e, s, n) ? (this.matches = s, this.matchElements = n, this.pointerHover(t, e, this.matches, this.matchElements), qt.add(i, gt ? nt.move : "mousemove", Wt.pointerHover)) : this.target && (k(o, i) ? (this.pointerHover(t, e, this.matches, this.matchElements), qt.add(this.element, gt ? nt.move : "mousemove", Wt.pointerHover)) : (this.element = this.target = null, this.matches = [], this.matchElements = [])))
                }
            }, pointerHover: function (t, e, i, r, s, n) {
                if (i = this.target, !this.prepared.name && this.mouse) {
                    var o;
                    this.setEventXY(this.curCoords, [t]), s ? o = this.validateSelector(t, e, s, n) : i && (o = j(i.getAction(this.pointers[0], e, this, this.element), this.target)), i && i.options.styleCursor && (i._doc.documentElement.style.cursor = o ? G(o) : "")
                } else this.prepared.name && this.checkAndPreventDefault(e, i, this.element)
            }, pointerOut: function (t, e, i) {
                this.prepared.name || (yt.get(i) || qt.remove(i, gt ? nt.move : "mousemove", Wt.pointerHover), this.target && this.target.options.styleCursor && !this.interacting() && (this.target._doc.documentElement.style.cursor = ""))
            }, selectorDown: function (t, e, r, s) {
                function n(t, e, i) {
                    i = ot ? i.querySelectorAll(e) : void 0, X(t, p) && !Y(t, p, r) && I(t, p, r) && rt(p, e, i) && (a.matches.push(t), a.matchElements.push(p))
                }

                var o, a = this, h = qt.useAttachEvent ? c({}, e) : e, p = r, l = this.addPointer(t);
                if (this.holdTimers[l] = setTimeout(function () {
                        a.pointerHold(qt.useAttachEvent ? h : t, h, r, s)
                    }, bt._holdDuration), this.pointerIsDown = !0, this.inertiaStatus.active && this.target.selector)for (; i(p);) {
                    if (p === this.element && j(this.target.getAction(t, e, this, this.element), this.target).name === this.prepared.name)return Nt(this.inertiaStatus.i), this.inertiaStatus.active = !1, void this.collectEventTargets(t, e, r, "down");
                    p = _(p)
                }
                if (!this.interacting()) {
                    for (this.setEventXY(this.curCoords, [t]), this.downEvent = e; i(p) && !o;)this.matches = [], this.matchElements = [], yt.forEachSelector(n), o = this.validateSelector(t, e, this.matches, this.matchElements), p = _(p);
                    if (o)return this.prepared.name = o.name, this.prepared.axis = o.axis, this.prepared.edges = o.edges, this.collectEventTargets(t, e, r, "down"), this.pointerDown(t, e, r, s, o);
                    this.downTimes[l] = (new Date).getTime(), this.downTargets[l] = r, d(this.downPointer, t), u(this.prevCoords, this.curCoords), this.pointerWasMoved = !1
                }
                this.collectEventTargets(t, e, r, "down")
            }, pointerDown: function (t, e, i, r, s) {
                if (!s && !this.inertiaStatus.active && this.pointerWasMoved && this.prepared.name)this.checkAndPreventDefault(e, this.target, this.element); else {
                    this.pointerIsDown = !0, this.downEvent = e;
                    var n, o = this.addPointer(t);
                    if (1 < this.pointerIds.length && this.target._element === this.element) {
                        var a = j(s || this.target.getAction(t, e, this, this.element), this.target);
                        q(this.target, this.element, a) && (n = a), this.prepared.name = null
                    } else this.prepared.name || (a = yt.get(r)) && !Y(a, r, i) && I(a, r, i) && (n = j(s || a.getAction(t, e, this, r), a, i)) && q(a, r, n) && (this.target = a, this.element = r);
                    var h = (a = this.target) && a.options;
                    !a || !s && this.prepared.name ? this.inertiaStatus.active && r === this.element && j(a.getAction(t, e, this, this.element), a).name === this.prepared.name && (Nt(this.inertiaStatus.i), this.inertiaStatus.active = !1, this.checkAndPreventDefault(e, a, this.element)) : (n = n || j(s || a.getAction(t, e, this, r), a, this.element), this.setEventXY(this.startCoords, this.pointers), n && (h.styleCursor && (a._doc.documentElement.style.cursor = G(n)), this.resizeAxes = "resize" === n.name ? n.axis : null, "gesture" === n && 2 > this.pointerIds.length && (n = null), this.prepared.name = n.name, this.prepared.axis = n.axis, this.prepared.edges = n.edges, this.snapStatus.snappedX = this.snapStatus.snappedY = this.restrictStatus.restrictedX = this.restrictStatus.restrictedY = NaN, this.downTimes[o] = (new Date).getTime(), this.downTargets[o] = i, d(this.downPointer, t), u(this.prevCoords, this.startCoords), this.pointerWasMoved = !1, this.checkAndPreventDefault(e, a, this.element)))
                }
            }, setModifications: function (t, e) {
                var i = this.target, r = !0, s = F(i, this.prepared.name) && (!i.options[this.prepared.name].snap.endOnly || e), i = N(i, this.prepared.name) && (!i.options[this.prepared.name].restrict.endOnly || e);
                return s ? this.setSnapping(t) : this.snapStatus.locked = !1, i ? this.setRestriction(t) : this.restrictStatus.restricted = !1, s && this.snapStatus.locked && !this.snapStatus.changed ? r = i && this.restrictStatus.restricted && this.restrictStatus.changed : i && this.restrictStatus.restricted && !this.restrictStatus.changed && (r = !1), r
            }, setStartOffsets: function (t, e, i) {
                t = e.getRect(i);
                var r = P(e, i);
                i = e.options[this.prepared.name].snap, e = e.options[this.prepared.name].restrict;
                var s, n;
                if (t ? (this.startOffset.left = this.startCoords.page.x - t.left, this.startOffset.top = this.startCoords.page.y - t.top, this.startOffset.right = t.right - this.startCoords.page.x, this.startOffset.bottom = t.bottom - this.startCoords.page.y, s = "width"in t ? t.width : t.right - t.left, n = "height"in t ? t.height : t.bottom - t.top) : this.startOffset.left = this.startOffset.top = this.startOffset.right = this.startOffset.bottom = 0, this.snapOffsets.splice(0), r = i && "startCoords" === i.offset ? {
                        x: this.startCoords.page.x - r.x,
                        y: this.startCoords.page.y - r.y
                    } : i && i.offset || {x: 0, y: 0}, t && i && i.relativePoints && i.relativePoints.length)for (var o = 0; o < i.relativePoints.length; o++)this.snapOffsets.push({
                    x: this.startOffset.left - s * i.relativePoints[o].x + r.x,
                    y: this.startOffset.top - n * i.relativePoints[o].y + r.y
                }); else this.snapOffsets.push(r);
                t && e.elementRect ? (this.restrictOffset.left = this.startOffset.left - s * e.elementRect.left, this.restrictOffset.top = this.startOffset.top - n * e.elementRect.top, this.restrictOffset.right = this.startOffset.right - s * (1 - e.elementRect.right), this.restrictOffset.bottom = this.startOffset.bottom - n * (1 - e.elementRect.bottom)) : this.restrictOffset.left = this.restrictOffset.top = this.restrictOffset.right = this.restrictOffset.bottom = 0
            }, start: function (t, e, i) {
                this.interacting() || !this.pointerIsDown || this.pointerIds.length < ("gesture" === t.name ? 2 : 1) || (-1 === it(xt, this) && xt.push(this), this.prepared.name || this.setEventXY(this.startCoords), this.prepared.name = t.name, this.prepared.axis = t.axis, this.prepared.edges = t.edges, this.target = e, this.element = i, this.setStartOffsets(t.name, e, i), this.setModifications(this.startCoords.page), this.prevEvent = this[this.prepared.name + "Start"](this.downEvent))
            }, pointerMove: function (t, e, r, s, n) {
                if (this.inertiaStatus.active) {
                    s = this.inertiaStatus.upCoords.page;
                    var o = this.inertiaStatus.upCoords.client;
                    this.setEventXY(this.curCoords, [{pageX: s.x + this.inertiaStatus.sx, pageY: s.y + this.inertiaStatus.sy, clientX: o.x + this.inertiaStatus.sx, clientY: o.y + this.inertiaStatus.sy}])
                } else this.recordPointer(t), this.setEventXY(this.curCoords, this.pointers);
                s = this.curCoords.page.x === this.prevCoords.page.x && this.curCoords.page.y === this.prevCoords.page.y && this.curCoords.client.x === this.prevCoords.client.x && this.curCoords.client.y === this.prevCoords.client.y;
                var a, h, o = this.mouse ? 0 : it(this.pointerIds, x(t));
                if (this.pointerIsDown && !this.pointerWasMoved && (a = this.curCoords.client.x - this.startCoords.client.x, h = this.curCoords.client.y - this.startCoords.client.y, this.pointerWasMoved = mt(a, h) > Ct), s || this.pointerIsDown && !this.pointerWasMoved || (this.pointerIsDown && clearTimeout(this.holdTimers[o]), this.collectEventTargets(t, e, r, "move")), this.pointerIsDown)if (s && this.pointerWasMoved && !n)this.checkAndPreventDefault(e, this.target, this.element); else if (g(this.pointerDelta, this.prevCoords, this.curCoords), this.prepared.name) {
                    if (this.pointerWasMoved && (!this.inertiaStatus.active || t instanceof V && /inertiastart/.test(t.type))) {
                        if (!this.interacting() && (g(this.pointerDelta, this.prevCoords, this.curCoords), "drag" === this.prepared.name)) {
                            a = Math.abs(a), h = Math.abs(h), s = this.target.options.drag.axis;
                            var p = a > h ? "x" : h > a ? "y" : "xy";
                            if ("xy" !== p && "xy" !== s && s !== p) {
                                this.prepared.name = null;
                                for (var l = r; i(l);) {
                                    if ((h = yt.get(l)) && h !== this.target && !h.options.drag.manualStart && "drag" === h.getAction(this.downPointer, this.downEvent, this, l).name && R(p, h)) {
                                        this.prepared.name = "drag", this.target = h, this.element = l;
                                        break
                                    }
                                    l = _(l)
                                }
                                if (!this.prepared.name) {
                                    var c = this;
                                    for (h = function (t, e, i) {
                                        return i = ot ? i.querySelectorAll(e) : void 0, t !== c.target && X(t, r) && !t.options.drag.manualStart && !Y(t, l, r) && I(t, l, r) && rt(l, e, i) && "drag" === t.getAction(c.downPointer, c.downEvent, c, l).name && R(p, t) && q(t, l, "drag") ? t : void 0
                                    }, l = r; i(l);) {
                                        if (a = yt.forEachSelector(h)) {
                                            this.prepared.name = "drag", this.target = a, this.element = l;
                                            break
                                        }
                                        l = _(l)
                                    }
                                }
                            }
                        }
                        if ((h = !!this.prepared.name && !this.interacting()) && (this.target.options[this.prepared.name].manualStart || !q(this.target, this.element, this.prepared)))return void this.stop(e);
                        this.prepared.name && this.target && (h && this.start(this.prepared, this.target, this.element), (this.setModifications(this.curCoords.page, n) || h) && (this.prevEvent = this[this.prepared.name + "Move"](e)), this.checkAndPreventDefault(e, this.target, this.element))
                    }
                    u(this.prevCoords, this.curCoords), (this.dragging || this.resizing) && this.autoScrollMove(t)
                }
            }, dragStart: function (t) {
                var e = new V(this, t, "drag", "start", this.element);
                return this.dragging = !0, this.target.fire(e), this.activeDrops.dropzones = [], this.activeDrops.elements = [], this.activeDrops.rects = [], this.dynamicDrop || this.setActiveDrops(this.element), t = this.getDropEvents(t, e), t.activate && this.fireActiveDrops(t.activate), e
            }, dragMove: function (t) {
                var e = this.target, i = new V(this, t, "drag", "move", this.element), r = this.getDrop(i, t, this.element);
                return this.dropTarget = r.dropzone, this.dropElement = r.element, t = this.getDropEvents(t, i), e.fire(i), t.leave && this.prevDropTarget.fire(t.leave), t.enter && this.dropTarget.fire(t.enter), t.move && this.dropTarget.fire(t.move), this.prevDropTarget = this.dropTarget, this.prevDropElement = this.dropElement, i
            }, resizeStart: function (t) {
                if (t = new V(this, t, "resize", "start", this.element), this.prepared.edges) {
                    var e = this.target.getRect(this.element);
                    if (this.target.options.resize.square || this.target.options.resize.preserveAspectRatio) {
                        var i = c({}, this.prepared.edges);
                        i.top = i.top || i.left && !i.bottom, i.left = i.left || i.top && !i.right, i.bottom = i.bottom || i.right && !i.top, i.right = i.right || i.bottom && !i.left,
                            this.prepared._linkedEdges = i
                    } else this.prepared._linkedEdges = null;
                    this.target.options.resize.preserveAspectRatio && (this.resizeStartAspectRatio = e.width / e.height), this.resizeRects = {
                        start: e,
                        current: c({}, e),
                        restricted: c({}, e),
                        previous: c({}, e),
                        delta: {left: 0, right: 0, width: 0, top: 0, bottom: 0, height: 0}
                    }, t.rect = this.resizeRects.restricted, t.deltaRect = this.resizeRects.delta
                }
                return this.target.fire(t), this.resizing = !0, t
            }, resizeMove: function (t) {
                t = new V(this, t, "resize", "move", this.element);
                var e = this.prepared.edges, i = this.target.options.resize.invert, r = "reposition" === i || "negate" === i;
                if (e) {
                    var s = t.dx, n = t.dy, o = this.resizeRects.start, a = this.resizeRects.current, h = this.resizeRects.restricted, p = this.resizeRects.delta, l = c(this.resizeRects.previous, h), d = e;
                    if (this.target.options.resize.preserveAspectRatio) {
                        var u = this.resizeStartAspectRatio, e = this.prepared._linkedEdges;
                        d.left && d.bottom || d.right && d.top ? n = -s / u : d.left || d.right ? n = s / u : (d.top || d.bottom) && (s = n * u)
                    } else this.target.options.resize.square && (e = this.prepared._linkedEdges, d.left && d.bottom || d.right && d.top ? n = -s : d.left || d.right ? n = s : (d.top || d.bottom) && (s = n));
                    e.top && (a.top += n), e.bottom && (a.bottom += n), e.left && (a.left += s), e.right && (a.right += s), r ? (c(h, a), "reposition" === i && (h.top > h.bottom && (e = h.top, h.top = h.bottom, h.bottom = e), h.left > h.right && (e = h.left, h.left = h.right, h.right = e))) : (h.top = Math.min(a.top, o.bottom), h.bottom = Math.max(a.bottom, o.top), h.left = Math.min(a.left, o.right), h.right = Math.max(a.right, o.left)), h.width = h.right - h.left, h.height = h.bottom - h.top;
                    for (var g in h)p[g] = h[g] - l[g];
                    t.edges = this.prepared.edges, t.rect = h, t.deltaRect = p
                }
                return this.target.fire(t), t
            }, gestureStart: function (t) {
                return t = new V(this, t, "gesture", "start", this.element), t.ds = 0, this.gesture.startDistance = this.gesture.prevDistance = t.distance, this.gesture.startAngle = this.gesture.prevAngle = t.angle, this.gesture.scale = 1, this.gesturing = !0, this.target.fire(t), t
            }, gestureMove: function (t) {
                return this.pointerIds.length ? (t = new V(this, t, "gesture", "move", this.element), t.ds = t.scale - this.gesture.scale, this.target.fire(t), this.gesture.prevAngle = t.angle, this.gesture.prevDistance = t.distance, 1 / 0 === t.scale || null === t.scale || void 0 === t.scale || isNaN(t.scale) || (this.gesture.scale = t.scale), t) : this.prevEvent
            }, pointerHold: function (t, e, i) {
                this.collectEventTargets(t, e, i, "hold")
            }, pointerUp: function (t, e, i, r) {
                var s = this.mouse ? 0 : it(this.pointerIds, x(t));
                clearTimeout(this.holdTimers[s]), this.collectEventTargets(t, e, i, "up"), this.collectEventTargets(t, e, i, "tap"), this.pointerEnd(t, e, i, r), this.removePointer(t)
            }, pointerCancel: function (t, e, i, r) {
                var s = this.mouse ? 0 : it(this.pointerIds, x(t));
                clearTimeout(this.holdTimers[s]), this.collectEventTargets(t, e, i, "cancel"), this.pointerEnd(t, e, i, r), this.removePointer(t)
            }, ie8Dblclick: function (t, e, i) {
                this.prevTap && e.clientX === this.prevTap.clientX && e.clientY === this.prevTap.clientY && i === this.prevTap.target && (this.downTargets[0] = i, this.downTimes[0] = (new Date).getTime(), this.collectEventTargets(t, e, i, "tap"))
            }, pointerEnd: function (t, e, i, r) {
                var s, n = this.target, o = n && n.options, a = o && this.prepared.name && o[this.prepared.name].inertia;
                if (s = this.inertiaStatus, this.interacting()) {
                    if (s.active && !s.ending)return;
                    var h = (new Date).getTime(), p = !1, l = !1, d = !1, g = F(n, this.prepared.name) && o[this.prepared.name].snap.endOnly, m = N(n, this.prepared.name) && o[this.prepared.name].restrict.endOnly, v = 0, f = 0, o = this.dragging ? "x" === o.drag.axis ? Math.abs(this.pointerDelta.client.vx) : "y" === o.drag.axis ? Math.abs(this.pointerDelta.client.vy) : this.pointerDelta.client.speed : this.pointerDelta.client.speed, l = (p = a && a.enabled && "gesture" !== this.prepared.name && e !== s.startEvent) && 50 > h - this.curCoords.timeStamp && o > a.minSpeed && o > a.endSpeed;
                    if (p && !l && (g || m) && (a = {}, a.snap = a.restrict = a, g && (this.setSnapping(this.curCoords.page, a), a.locked && (v += a.dx, f += a.dy)), m && (this.setRestriction(this.curCoords.page, a), a.restricted && (v += a.dx, f += a.dy)), v || f) && (d = !0), l || d)return u(s.upCoords, this.curCoords), this.pointers[0] = s.startEvent = new V(this, e, this.prepared.name, "inertiastart", this.element), s.t0 = h, n.fire(s.startEvent), l ? (s.vx0 = this.pointerDelta.client.vx, s.vy0 = this.pointerDelta.client.vy, s.v0 = o, this.calcInertia(s), e = c({}, this.curCoords.page), n = P(n, this.element), e.x = e.x + s.xe - n.x, e.y = e.y + s.ye - n.y, n = {
                        useStatusXY: !0,
                        x: e.x,
                        y: e.y,
                        dx: 0,
                        dy: 0,
                        snap: null
                    }, n.snap = n, v = f = 0, g && (e = this.setSnapping(this.curCoords.page, n), e.locked && (v += e.dx, f += e.dy)), m && (n = this.setRestriction(this.curCoords.page, n), n.restricted && (v += n.dx, f += n.dy)), s.modifiedXe += v, s.modifiedYe += f, s.i = Ft(this.boundInertiaFrame)) : (s.smoothEnd = !0, s.xe = v, s.ye = f, s.sx = s.sy = 0, s.i = Ft(this.boundSmoothEndFrame)), void(s.active = !0);
                    (g || m) && this.pointerMove(t, e, i, r, !0)
                }
                this.dragging ? (s = new V(this, e, "drag", "end", this.element), m = this.getDrop(s, e, this.element), this.dropTarget = m.dropzone, this.dropElement = m.element, m = this.getDropEvents(e, s), m.leave && this.prevDropTarget.fire(m.leave), m.enter && this.dropTarget.fire(m.enter), m.drop && this.dropTarget.fire(m.drop), m.deactivate && this.fireActiveDrops(m.deactivate), n.fire(s)) : this.resizing ? (s = new V(this, e, "resize", "end", this.element), n.fire(s)) : this.gesturing && (s = new V(this, e, "gesture", "end", this.element), n.fire(s)), this.stop(e)
            }, collectDrops: function (t) {
                var e, r = [], s = [];
                for (t = t || this.element, e = 0; e < yt.length; e++)if (yt[e].options.drop.enabled) {
                    var n = yt[e], o = n.options.drop.accept;
                    if (!(i(o) && o !== t || p(o) && !rt(t, o)))for (var o = n.selector ? n._context.querySelectorAll(n.selector) : [n._element], a = 0, h = o.length; h > a; a++) {
                        var l = o[a];
                        l !== t && (r.push(n), s.push(l))
                    }
                }
                return {dropzones: r, elements: s}
            }, fireActiveDrops: function (t) {
                var e, i, r, s;
                for (e = 0; e < this.activeDrops.dropzones.length; e++)i = this.activeDrops.dropzones[e], r = this.activeDrops.elements[e], r !== s && (t.target = r, i.fire(t)), s = r
            }, setActiveDrops: function (t) {
                for (t = this.collectDrops(t, !0), this.activeDrops.dropzones = t.dropzones, this.activeDrops.elements = t.elements, this.activeDrops.rects = [], t = 0; t < this.activeDrops.dropzones.length; t++)this.activeDrops.rects[t] = this.activeDrops.dropzones[t].getRect(this.activeDrops.elements[t])
            }, getDrop: function (t, e, i) {
                var r = [];
                Et && this.setActiveDrops(i);
                for (var s = 0; s < this.activeDrops.dropzones.length; s++) {
                    var n = this.activeDrops.elements[s];
                    r.push(this.activeDrops.dropzones[s].dropCheck(t, e, this.target, i, n, this.activeDrops.rects[s]) ? n : null)
                }
                i = (e = r[0]) ? 0 : -1;
                for (var o, s = [], a = [], n = 1; n < r.length; n++)if ((t = r[n]) && t !== e)if (e) {
                    if (t.parentNode !== t.ownerDocument)if (e.parentNode === t.ownerDocument)e = t, i = n; else {
                        if (!s.length)for (o = e; o.parentNode && o.parentNode !== o.ownerDocument;)s.unshift(o), o = o.parentNode;
                        if (e instanceof ut && t instanceof lt && !(t instanceof ct)) {
                            if (t === e.parentNode)continue;
                            o = t.ownerSVGElement
                        } else o = t;
                        for (a = []; o.parentNode !== o.ownerDocument;)a.unshift(o), o = o.parentNode;
                        for (o = 0; a[o] && a[o] === s[o];)o++;
                        for (o = [a[o - 1], a[o], s[o]], a = o[0].lastChild; a;) {
                            if (a === o[1]) {
                                e = t, i = n, s = [];
                                break
                            }
                            if (a === o[2])break;
                            a = a.previousSibling
                        }
                    }
                } else e = t, i = n;
                return r = i, {dropzone: this.activeDrops.dropzones[r] || null, element: this.activeDrops.elements[r] || null}
            }, getDropEvents: function (t, e) {
                var i = {enter: null, leave: null, activate: null, deactivate: null, move: null, drop: null};
                return this.dropElement !== this.prevDropElement && (this.prevDropTarget && (i.leave = {
                    target: this.prevDropElement,
                    dropzone: this.prevDropTarget,
                    relatedTarget: e.target,
                    draggable: e.interactable,
                    dragEvent: e,
                    interaction: this,
                    timeStamp: e.timeStamp,
                    type: "dragleave"
                }, e.dragLeave = this.prevDropElement, e.prevDropzone = this.prevDropTarget), this.dropTarget && (i.enter = {
                    target: this.dropElement,
                    dropzone: this.dropTarget,
                    relatedTarget: e.target,
                    draggable: e.interactable,
                    dragEvent: e,
                    interaction: this,
                    timeStamp: e.timeStamp,
                    type: "dragenter"
                }, e.dragEnter = this.dropElement, e.dropzone = this.dropTarget)), "dragend" === e.type && this.dropTarget && (i.drop = {
                    target: this.dropElement,
                    dropzone: this.dropTarget,
                    relatedTarget: e.target,
                    draggable: e.interactable,
                    dragEvent: e,
                    interaction: this,
                    timeStamp: e.timeStamp,
                    type: "drop"
                }, e.dropzone = this.dropTarget), "dragstart" === e.type && (i.activate = {target: null, dropzone: null, relatedTarget: e.target, draggable: e.interactable, dragEvent: e, interaction: this, timeStamp: e.timeStamp, type: "dropactivate"}), "dragend" === e.type && (i.deactivate = {
                    target: null,
                    dropzone: null,
                    relatedTarget: e.target,
                    draggable: e.interactable,
                    dragEvent: e,
                    interaction: this,
                    timeStamp: e.timeStamp,
                    type: "dropdeactivate"
                }), "dragmove" === e.type && this.dropTarget && (i.move = {target: this.dropElement, dropzone: this.dropTarget, relatedTarget: e.target, draggable: e.interactable, dragEvent: e, interaction: this, dragmove: e, timeStamp: e.timeStamp, type: "dropmove"}, e.dropzone = this.dropTarget), i
            }, currentAction: function () {
                return this.dragging && "drag" || this.resizing && "resize" || this.gesturing && "gesture" || null
            }, interacting: function () {
                return this.dragging || this.resizing || this.gesturing
            }, clearTargets: function () {
                this.dropTarget = this.dropElement = this.prevDropTarget = this.prevDropElement = this.target = this.element = null
            }, stop: function (t) {
                if (this.interacting()) {
                    wt.stop(), this.matches = [], this.matchElements = [];
                    var e = this.target;
                    e.options.styleCursor && (e._doc.documentElement.style.cursor = ""), t && o(t.preventDefault) && this.checkAndPreventDefault(t, e, this.element), this.dragging && (this.activeDrops.dropzones = this.activeDrops.elements = this.activeDrops.rects = null)
                }
                for (this.clearTargets(), this.pointerIsDown = this.snapStatus.locked = this.dragging = this.resizing = this.gesturing = !1, this.prepared.name = this.prevEvent = null, t = this.inertiaStatus.resumeDx = this.inertiaStatus.resumeDy = 0; t < this.pointers.length; t++)-1 === it(this.pointerIds, x(this.pointers[t])) && this.pointers.splice(t, 1)
            }, inertiaFrame: function () {
                var t, e, i = this.inertiaStatus;
                if (t = this.target.options[this.prepared.name].inertia.resistance, e = (new Date).getTime() / 1e3 - i.t0, e < i.te) {
                    if (e = 1 - (Math.exp(-t * e) - i.lambda_v0) / i.one_ve_v0, i.modifiedXe === i.xe && i.modifiedYe === i.ye)i.sx = i.xe * e, i.sy = i.ye * e; else {
                        var r = i.ye, s = i.modifiedYe;
                        t = A(e, 0, i.xe, i.modifiedXe), e = A(e, 0, r, s), i.sx = t, i.sy = e
                    }
                    this.pointerMove(i.startEvent, i.startEvent), i.i = Ft(this.boundInertiaFrame)
                } else i.ending = !0, i.sx = i.modifiedXe, i.sy = i.modifiedYe, this.pointerMove(i.startEvent, i.startEvent), this.pointerEnd(i.startEvent, i.startEvent), i.active = i.ending = !1
            }, smoothEndFrame: function () {
                var t = this.inertiaStatus, e = (new Date).getTime() - t.t0, i = this.target.options[this.prepared.name].inertia.smoothEndDuration;
                if (i > e) {
                    var r;
                    r = e / i, t.sx = -t.xe * r * (r - 2) + 0, e /= i, t.sy = -t.ye * e * (e - 2) + 0, this.pointerMove(t.startEvent, t.startEvent), t.i = Ft(this.boundSmoothEndFrame)
                } else t.ending = !0, t.sx = t.xe, t.sy = t.ye, this.pointerMove(t.startEvent, t.startEvent), this.pointerEnd(t.startEvent, t.startEvent), t.smoothEnd = t.active = t.ending = !1
            }, addPointer: function (t) {
                var e = x(t), i = this.mouse ? 0 : it(this.pointerIds, e);
                return -1 === i && (i = this.pointerIds.length), this.pointerIds[i] = e, this.pointers[i] = t, i
            }, removePointer: function (t) {
                t = x(t), t = this.mouse ? 0 : it(this.pointerIds, t), -1 !== t && (this.pointers.splice(t, 1), this.pointerIds.splice(t, 1), this.downTargets.splice(t, 1), this.downTimes.splice(t, 1), this.holdTimers.splice(t, 1))
            }, recordPointer: function (t) {
                var e = this.mouse ? 0 : it(this.pointerIds, x(t));
                -1 !== e && (this.pointers[e] = t)
            }, collectEventTargets: function (t, e, r, s) {
                function n(t, e, n) {
                    n = ot ? n.querySelectorAll(e) : void 0, t._iEvents[s] && i(p) && X(t, p) && !Y(t, p, r) && I(t, p, r) && rt(p, e, n) && (a.push(t), h.push(p))
                }

                var o = this.mouse ? 0 : it(this.pointerIds, x(t));
                if ("tap" !== s || !this.pointerWasMoved && this.downTargets[o] && this.downTargets[o] === r) {
                    for (var a = [], h = [], p = r; p;)J.isSet(p) && J(p)._iEvents[s] && (a.push(J(p)), h.push(p)), yt.forEachSelector(n), p = _(p);
                    (a.length || "tap" === s) && this.firePointers(t, e, r, a, h, s)
                }
            }, firePointers: function (t, e, i, r, s, n) {
                var o, a, h = this.mouse ? 0 : it(this.pointerIds, x(t)), l = {};
                for ("doubletap" === n ? l = t : (d(l, e), e !== t && d(l, t), l.preventDefault = $, l.stopPropagation = V.prototype.stopPropagation, l.stopImmediatePropagation = V.prototype.stopImmediatePropagation, l.interaction = this, l.timeStamp = (new Date).getTime(), l.originalEvent = e, l.originalPointer = t, l.type = n, l.pointerId = x(t), l.pointerType = this.mouse ? "mouse" : Dt ? p(t.pointerType) ? t.pointerType : [, , "touch", "pen", "mouse"][t.pointerType] : "touch"), "tap" === n && (l.dt = l.timeStamp - this.downTimes[h], o = l.timeStamp - this.tapTime, a = !!(this.prevTap && "doubletap" !== this.prevTap.type && this.prevTap.target === l.target && 500 > o), l["double"] = a, this.tapTime = l.timeStamp), t = 0; t < r.length && (l.currentTarget = s[t], l.interactable = r[t], r[t].fire(l), !(l.immediatePropagationStopped || l.propagationStopped && s[t + 1] !== l.currentTarget)); t++);
                a ? (r = {}, c(r, l), r.dt = o, r.type = "doubletap", this.collectEventTargets(r, e, i, "doubletap"), this.prevTap = r) : "tap" === n && (this.prevTap = l)
            }, validateSelector: function (t, e, i, r) {
                for (var s = 0, n = i.length; n > s; s++) {
                    var o = i[s], a = r[s], h = j(o.getAction(t, e, this, a), o);
                    if (h && q(o, a, h))return this.target = o, this.element = a, h
                }
            }, setSnapping: function (t, e) {
                var i, r, s, n = this.target.options[this.prepared.name].snap, h = [];
                e = e || this.snapStatus, e.useStatusXY ? r = {x: e.x, y: e.y} : (i = P(this.target, this.element), r = c({}, t), r.x -= i.x, r.y -= i.y), e.realX = r.x, e.realY = r.y, r.x -= this.inertiaStatus.resumeDx, r.y -= this.inertiaStatus.resumeDy;
                for (var p = n.targets ? n.targets.length : 0, l = 0; l < this.snapOffsets.length; l++) {
                    var d = r.x - this.snapOffsets[l].x, u = r.y - this.snapOffsets[l].y;
                    for (s = 0; p > s; s++)(i = o(n.targets[s]) ? n.targets[s](d, u, this) : n.targets[s]) && h.push({x: a(i.x) ? i.x + this.snapOffsets[l].x : d, y: a(i.y) ? i.y + this.snapOffsets[l].y : u, range: a(i.range) ? i.range : n.range})
                }
                var n = null, l = !1, g = 0, m = 0;
                for (s = u = d = 0, p = h.length; p > s; s++) {
                    i = h[s];
                    var v = i.range, f = i.x - r.x, y = i.y - r.y, x = mt(f, y), E = v >= x;
                    1 / 0 === v && l && 1 / 0 !== m && (E = !1), (!n || (E ? l && 1 / 0 !== v ? g / m > x / v : 1 / 0 === v && 1 / 0 !== m || g > x : !l && g > x)) && (1 / 0 === v && (E = !0), n = i, g = x, m = v, l = E, d = f, u = y, e.range = v)
                }
                return n ? (h = e.snappedX !== n.x || e.snappedY !== n.y, e.snappedX = n.x, e.snappedY = n.y) : (h = !0, e.snappedX = NaN, e.snappedY = NaN), e.dx = d, e.dy = u, e.changed = h || l && !e.locked, e.locked = l, e
            }, setRestriction: function (t, e) {
                var r = this.target, s = r && r.options[this.prepared.name].restrict, n = s && s.restriction;
                return n ? (e = e || this.restrictStatus, s = s = e.useStatusXY ? {
                    x: e.x,
                    y: e.y
                } : c({}, t), e.snap && e.snap.locked && (s.x += e.snap.dx || 0, s.y += e.snap.dy || 0), s.x -= this.inertiaStatus.resumeDx, s.y -= this.inertiaStatus.resumeDy, e.dx = 0, e.dy = 0, e.restricted = !1, p(n) && (n = "parent" === n ? _(this.element) : "self" === n ? r.getRect(this.element) : O(this.element, n), !n) ? e : (o(n) && (n = n(s.x, s.y, this.element)), i(n) && (n = w(n)), (r = n) ? "x"in n && "y"in n ? (n = Math.max(Math.min(r.x + r.width - this.restrictOffset.right, s.x), r.x + this.restrictOffset.left), r = Math.max(Math.min(r.y + r.height - this.restrictOffset.bottom, s.y), r.y + this.restrictOffset.top)) : (n = Math.max(Math.min(r.right - this.restrictOffset.right, s.x), r.left + this.restrictOffset.left), r = Math.max(Math.min(r.bottom - this.restrictOffset.bottom, s.y), r.top + this.restrictOffset.top)) : (n = s.x, r = s.y), e.dx = n - s.x, e.dy = r - s.y, e.changed = e.restrictedX !== n || e.restrictedY !== r, e.restricted = !(!e.dx && !e.dy), e.restrictedX = n, e.restrictedY = r, e)) : e
            }, checkAndPreventDefault: function (t, e, i) {
                if (e = e || this.target) {
                    e = e.options;
                    var r = e.preventDefault;
                    "auto" === r && i && !/^(input|select|textarea)$/i.test(t.target.nodeName) ? /down|start/i.test(t.type) && "drag" === this.prepared.name && "xy" !== e.drag.axis || e[this.prepared.name] && e[this.prepared.name].manualStart && !this.interacting() || t.preventDefault() : "always" === r && t.preventDefault()
                }
            }, calcInertia: function (t) {
                var e = this.target.options[this.prepared.name].inertia, i = e.resistance, r = -Math.log(e.endSpeed / t.v0) / i;
                t.x0 = this.prevEvent.pageX, t.y0 = this.prevEvent.pageY, t.t0 = t.startEvent.timeStamp / 1e3, t.sx = t.sy = 0, t.modifiedXe = t.xe = (t.vx0 - r) / i, t.modifiedYe = t.ye = (t.vy0 - r) / i, t.te = r, t.lambda_v0 = i / t.v0, t.one_ve_v0 = 1 - e.endSpeed / t.v0
            }, autoScrollMove: function (t) {
                var e;
                if (e = this.interacting()) {
                    e = this.prepared.name;
                    var i = this.target.options;
                    /^resize/.test(e) && (e = "resize"), e = i[e].autoScroll && i[e].autoScroll.enabled
                }
                if (e)if (this.inertiaStatus.active)wt.x = wt.y = 0; else {
                    var s, n = this.target.options[this.prepared.name].autoScroll, o = n.container || S(this.element);
                    r(o) ? (s = t.clientX < wt.margin, e = t.clientY < wt.margin, i = t.clientX > o.innerWidth - wt.margin, t = t.clientY > o.innerHeight - wt.margin) : (o = b(o), s = t.clientX < o.left + wt.margin, e = t.clientY < o.top + wt.margin, i = t.clientX > o.right - wt.margin, t = t.clientY > o.bottom - wt.margin), wt.x = i ? 1 : s ? -1 : 0, wt.y = t ? 1 : e ? -1 : 0, wt.isScrolling || (wt.margin = n.margin, wt.speed = n.speed, wt.start(this))
                }
            }, _updateEventTargets: function (t, e) {
                this._eventTarget = t, this._curEventTarget = e
            }
        }, V.prototype = {
            preventDefault: e, stopImmediatePropagation: function () {
                this.immediatePropagationStopped = this.propagationStopped = !0
            }, stopPropagation: function () {
                this.propagationStopped = !0
            }
        };
        for (var Wt = {}, Ut = "dragStart dragMove resizeStart resizeMove gestureStart gestureMove pointerOver pointerOut pointerHover selectorDown pointerDown pointerMove pointerUp pointerCancel pointerEnd addPointer removePointer recordPointer autoScrollMove".split(" "), Vt = 0, $t = Ut.length; $t > Vt; Vt++) {
            var Gt = Ut[Vt];
            Wt[Gt] = U(Gt)
        }
        yt.indexOfElement = function (t, e) {
            e = e || ht;
            for (var i = 0; i < this.length; i++) {
                var r = this[i];
                if (r.selector === t && r._context === e || !r.selector && r._element === t)return i
            }
            return -1
        }, yt.get = function (t, e) {
            return this[this.indexOfElement(t, e && e.context)]
        }, yt.forEachSelector = function (t) {
            for (var e = 0; e < this.length; e++) {
                var i = this[e];
                if (i.selector && (i = t(i, i.selector, i._context, e, this), void 0 !== i))return i
            }
        }, Q.prototype = {
            setOnEvents: function (t, e) {
                return "drop" === t ? (o(e.ondrop) && (this.ondrop = e.ondrop), o(e.ondropactivate) && (this.ondropactivate = e.ondropactivate), o(e.ondropdeactivate) && (this.ondropdeactivate = e.ondropdeactivate), o(e.ondragenter) && (this.ondragenter = e.ondragenter), o(e.ondragleave) && (this.ondragleave = e.ondragleave), o(e.ondropmove) && (this.ondropmove = e.ondropmove)) : (t = "on" + t, o(e.onstart) && (this[t + "start"] = e.onstart), o(e.onmove) && (this[t + "move"] = e.onmove), o(e.onend) && (this[t + "end"] = e.onend), o(e.oninertiastart) && (this[t + "inertiastart"] = e.oninertiastart)), this
            }, draggable: function (t) {
                return n(t) ? (this.options.drag.enabled = !1 === t.enabled ? !1 : !0, this.setPerAction("drag", t), this.setOnEvents("drag", t), /^x$|^y$|^xy$/.test(t.axis) ? this.options.drag.axis = t.axis : null === t.axis && delete this.options.drag.axis, this) : h(t) ? (this.options.drag.enabled = t, this) : this.options.drag
            }, setPerAction: function (t, e) {
                for (var i in e)i in bt[t] && (n(e[i]) ? (this.options[t][i] = c(this.options[t][i] || {}, e[i]), n(bt.perAction[i]) && "enabled"in bt.perAction[i] && (this.options[t][i].enabled = !1 === e[i].enabled ? !1 : !0)) : h(e[i]) && n(bt.perAction[i]) ? this.options[t][i].enabled = e[i] : void 0 !== e[i] && (this.options[t][i] = e[i]))
            }, dropzone: function (t) {
                return n(t) ? (this.options.drop.enabled = !1 === t.enabled ? !1 : !0, this.setOnEvents("drop", t), /^(pointer|center)$/.test(t.overlap) ? this.options.drop.overlap = t.overlap : a(t.overlap) && (this.options.drop.overlap = Math.max(Math.min(1, t.overlap), 0)), "accept"in t && (this.options.drop.accept = t.accept), "checker"in t && (this.options.drop.checker = t.checker), this) : h(t) ? (this.options.drop.enabled = t, this) : this.options.drop
            }, dropCheck: function (t, e, i, r, s, n) {
                var o = !1;
                if (!(n = n || this.getRect(s)))return this.options.drop.checker ? this.options.drop.checker(t, e, o, this, s, i, r) : !1;
                var h = this.options.drop.overlap;
                if ("pointer" === h) {
                    var p = f(t), o = P(i, r);
                    p.x += o.x, p.y += o.y, o = p.x > n.left && p.x < n.right, p = p.y > n.top && p.y < n.bottom, o = o && p
                }
                if (p = i.getRect(r), "center" === h)var o = p.left + p.width / 2, l = p.top + p.height / 2, o = o >= n.left && o <= n.right && l >= n.top && l <= n.bottom;
                return a(h) && (o = Math.max(0, Math.min(n.right, p.right) - Math.max(n.left, p.left)) * Math.max(0, Math.min(n.bottom, p.bottom) - Math.max(n.top, p.top)) / (p.width * p.height) >= h), this.options.drop.checker && (o = this.options.drop.checker(t, e, o, this, s, i, r)), o
            }, dropChecker: function (t) {
                return o(t) ? (this.options.drop.checker = t, this) : null === t ? (delete this.options.getRect, this) : this.options.drop.checker
            }, accept: function (t) {
                return i(t) || l(t) ? (this.options.drop.accept = t, this) : null === t ? (delete this.options.drop.accept, this) : this.options.drop.accept
            }, resizable: function (t) {
                return n(t) ? (this.options.resize.enabled = !1 === t.enabled ? !1 : !0, this.setPerAction("resize", t), this.setOnEvents("resize", t), /^x$|^y$|^xy$/.test(t.axis) ? this.options.resize.axis = t.axis : null === t.axis && (this.options.resize.axis = bt.resize.axis), h(t.preserveAspectRatio) ? this.options.resize.preserveAspectRatio = t.preserveAspectRatio : h(t.square) && (this.options.resize.square = t.square), this) : h(t) ? (this.options.resize.enabled = t, this) : this.options.resize
            }, squareResize: function (t) {
                return h(t) ? (this.options.resize.square = t, this) : null === t ? (delete this.options.resize.square, this) : this.options.resize.square
            }, gesturable: function (t) {
                return n(t) ? (this.options.gesture.enabled = !1 === t.enabled ? !1 : !0, this.setPerAction("gesture", t), this.setOnEvents("gesture", t), this) : h(t) ? (this.options.gesture.enabled = t, this) : this.options.gesture
            }, autoScroll: function (t) {
                return n(t) ? t = c({actions: ["drag", "resize"]}, t) : h(t) && (t = {actions: ["drag", "resize"], enabled: t}), this.setOptions("autoScroll", t)
            }, snap: function (t) {
                return t = this.setOptions("snap", t), t === this ? this : t.drag
            }, setOptions: function (t, e) {
                var i, r = e && s(e.actions) ? e.actions : ["drag"];
                if (n(e) || h(e)) {
                    for (i = 0; i < r.length; i++) {
                        var o = /resize/.test(r[i]) ? "resize" : r[i];
                        n(this.options[o]) && (o = this.options[o][t], n(e) ? (c(o, e), o.enabled = !1 === e.enabled ? !1 : !0, "snap" === t && ("grid" === o.mode ? o.targets = [J.createSnapGrid(c({
                            offset: o.gridOffset || {
                                x: 0,
                                y: 0
                            }
                        }, o.grid || {}))] : "anchor" === o.mode ? o.targets = o.anchors : "path" === o.mode && (o.targets = o.paths), "elementOrigin"in e && (o.relativePoints = [e.elementOrigin]))) : h(e) && (o.enabled = e))
                    }
                    return this
                }
                for (r = {}, o = ["drag", "resize", "gesture"], i = 0; i < o.length; i++)t in bt[o[i]] && (r[o[i]] = this.options[o[i]][t]);
                return r
            }, inertia: function (t) {
                return t = this.setOptions("inertia", t), t === this ? this : t.drag
            }, getAction: function (t, e, i, r) {
                var s = this.defaultActionChecker(t, i, r);
                return this.options.actionChecker ? this.options.actionChecker(t, e, s, this, r, i) : s
            }, defaultActionChecker: L, actionChecker: function (t) {
                return o(t) ? (this.options.actionChecker = t, this) : null === t ? (delete this.options.actionChecker, this) : this.options.actionChecker
            }, getRect: function (t) {
                return t = t || this._element, this.selector && !i(t) && (t = this._context.querySelector(this.selector)), w(t)
            }, rectChecker: function (t) {
                return o(t) ? (this.getRect = t, this) : null === t ? (delete this.options.getRect, this) : this.getRect
            }, styleCursor: function (t) {
                return h(t) ? (this.options.styleCursor = t, this) : null === t ? (delete this.options.styleCursor, this) : this.options.styleCursor
            }, preventDefault: function (t) {
                return /^(always|never|auto)$/.test(t) ? (this.options.preventDefault = t, this) : h(t) ? (this.options.preventDefault = t ? "always" : "never", this) : this.options.preventDefault
            }, origin: function (t) {
                return l(t) || n(t) ? (this.options.origin = t, this) : this.options.origin
            }, deltaSource: function (t) {
                return "page" === t || "client" === t ? (this.options.deltaSource = t, this) : this.options.deltaSource
            }, restrict: function (t) {
                if (!n(t))return this.setOptions("restrict", t);
                for (var e, i = ["drag", "resize", "gesture"], r = 0; r < i.length; r++) {
                    var s = i[r];
                    s in t && (e = c({actions: [s], restriction: t[s]}, t), e = this.setOptions("restrict", e))
                }
                return e
            }, context: function () {
                return this._context
            }, _context: ht, ignoreFrom: function (t) {
                return l(t) || i(t) ? (this.options.ignoreFrom = t, this) : this.options.ignoreFrom
            }, allowFrom: function (t) {
                return l(t) || i(t) ? (this.options.allowFrom = t, this) : this.options.allowFrom
            }, element: function () {
                return this._element
            }, fire: function (t) {
                if (!t || !t.type || -1 === it(_t, t.type))return this;
                var e, i, r, s = "on" + t.type;
                if (t.type in this._iEvents)for (e = this._iEvents[t.type], i = 0, r = e.length; r > i && !t.immediatePropagationStopped; i++)e[i](t);
                if (o(this[s]) && this[s](t), t.type in Xt && (e = Xt[t.type]))for (i = 0, r = e.length; r > i && !t.immediatePropagationStopped; i++)e[i](t);
                return this
            }, on: function (t, e, i) {
                var r;
                if (p(t) && -1 !== t.search(" ") && (t = t.trim().split(/ +/)), s(t)) {
                    for (r = 0; r < t.length; r++)this.on(t[r], e, i);
                    return this
                }
                if (n(t)) {
                    for (r in t)this.on(r, t[r], e);
                    return this
                }
                if ("wheel" === t && (t = Ot), i = i ? !0 : !1, -1 !== it(_t, t))t in this._iEvents ? this._iEvents[t].push(e) : this._iEvents[t] = [e]; else if (this.selector) {
                    if (!St[t])for (St[t] = {selectors: [], contexts: [], listeners: []}, r = 0; r < ft.length; r++)qt.add(ft[r], t, B), qt.add(ft[r], t, K, !0);
                    for (t = St[t], r = t.selectors.length - 1; r >= 0 && (t.selectors[r] !== this.selector || t.contexts[r] !== this._context); r--);
                    -1 === r && (r = t.selectors.length, t.selectors.push(this.selector), t.contexts.push(this._context), t.listeners.push([])), t.listeners[r].push([e, i])
                } else qt.add(this._element, t, e, i);
                return this
            }, off: function (t, e, i) {
                var r;
                if (p(t) && -1 !== t.search(" ") && (t = t.trim().split(/ +/)), s(t)) {
                    for (r = 0; r < t.length; r++)this.off(t[r], e, i);
                    return this
                }
                if (n(t)) {
                    for (var o in t)this.off(o, t[o], e);
                    return this
                }
                if (o = -1, i = i ? !0 : !1, "wheel" === t && (t = Ot), -1 !== it(_t, t))(i = this._iEvents[t]) && -1 !== (o = it(i, e)) && this._iEvents[t].splice(o, 1); else if (this.selector) {
                    var a = St[t], h = !1;
                    if (!a)return this;
                    for (o = a.selectors.length - 1; o >= 0; o--)if (a.selectors[o] === this.selector && a.contexts[o] === this._context) {
                        var l = a.listeners[o];
                        for (r = l.length - 1; r >= 0; r--) {
                            var c = l[r][1];
                            if (l[r][0] === e && c === i) {
                                l.splice(r, 1), l.length || (a.selectors.splice(o, 1), a.contexts.splice(o, 1), a.listeners.splice(o, 1), qt.remove(this._context, t, B), qt.remove(this._context, t, K, !0), a.selectors.length || (St[t] = null)), h = !0;
                                break
                            }
                        }
                        if (h)break
                    }
                } else qt.remove(this._element, t, e, i);
                return this
            }, set: function (t) {
                n(t) || (t = {}), this.options = c({}, bt.base);
                var e, i = ["drag", "drop", "resize", "gesture"], r = ["draggable", "dropzone", "resizable", "gesturable"], s = c(c({}, bt.perAction), t[o] || {});
                for (e = 0; e < i.length; e++) {
                    var o = i[e];
                    this.options[o] = c({}, bt[o]), this.setPerAction(o, s), this[r[e]](t[o])
                }
                for (i = "accept actionChecker allowFrom deltaSource dropChecker ignoreFrom origin preventDefault rectChecker styleCursor".split(" "), e = 0, $t = i.length; $t > e; e++)r = i[e], this.options[r] = bt.base[r], r in t && this[r](t[r]);
                return this
            }, unset: function () {
                if (qt.remove(this._element, "all"), p(this.selector))for (var t in St)for (var e = St[t]; 0 < e.selectors.length;) {
                    e.selectors[0] === this.selector && e.contexts[0] === this._context && (e.selectors.splice(0, 1), e.contexts.splice(0, 1), e.listeners.splice(0, 1), e.selectors.length || (St[t] = null)), qt.remove(this._context, t, B), qt.remove(this._context, t, K, !0);
                    break
                } else qt.remove(this, "all"), this.options.styleCursor && (this._element.style.cursor = "");
                return this.dropzone(!1), yt.splice(it(yt, this), 1), J
            }
        }, Q.prototype.snap = Z(Q.prototype.snap, "Interactable#snap is deprecated. See the new documentation for snapping at http://interactjs.io/docs/snapping"), Q.prototype.restrict = Z(Q.prototype.restrict, "Interactable#restrict is deprecated. See the new documentation for resticting at http://interactjs.io/docs/restriction"), Q.prototype.inertia = Z(Q.prototype.inertia, "Interactable#inertia is deprecated. See the new documentation for inertia at http://interactjs.io/docs/inertia"), Q.prototype.autoScroll = Z(Q.prototype.autoScroll, "Interactable#autoScroll is deprecated. See the new documentation for autoScroll at http://interactjs.io/docs/#autoscroll"), Q.prototype.squareResize = Z(Q.prototype.squareResize, "Interactable#squareResize is deprecated. See http://interactjs.io/docs/#resize-square"), Q.prototype.accept = Z(Q.prototype.accept, "Interactable#accept is deprecated. use Interactable#dropzone({ accept: target }) instead"), Q.prototype.dropChecker = Z(Q.prototype.dropChecker, "Interactable#dropChecker is deprecated. use Interactable#dropzone({ dropChecker: checkerFunction }) instead"), Q.prototype.context = Z(Q.prototype.context, "Interactable#context as a method is deprecated. It will soon be a DOM Node instead"), J.isSet = function (t, e) {
            return -1 !== yt.indexOfElement(t, e && e.context)
        }, J.on = function (t, e, i) {
            if (p(t) && -1 !== t.search(" ") && (t = t.trim().split(/ +/)), s(t)) {
                for (var r = 0; r < t.length; r++)J.on(t[r], e, i);
                return J
            }
            if (n(t)) {
                for (r in t)J.on(r, t[r], e);
                return J
            }
            return -1 !== it(_t, t) ? Xt[t] ? Xt[t].push(e) : Xt[t] = [e] : qt.add(ht, t, e, i), J
        }, J.off = function (t, e, i) {
            if (p(t) && -1 !== t.search(" ") && (t = t.trim().split(/ +/)), s(t)) {
                for (var r = 0; r < t.length; r++)J.off(t[r], e, i);
                return J
            }
            if (n(t)) {
                for (r in t)J.off(r, t[r], e);
                return J
            }
            if (-1 === it(_t, t))qt.remove(ht, t, e, i); else {
                var o;
                t in Xt && -1 !== (o = it(Xt[t], e)) && Xt[t].splice(o, 1)
            }
            return J
        }, J.enableDragging = Z(function (t) {
            return null !== t && void 0 !== t ? (kt.drag = t, J) : kt.drag
        }, "interact.enableDragging is deprecated and will soon be removed."), J.enableResizing = Z(function (t) {
            return null !== t && void 0 !== t ? (kt.resize = t, J) : kt.resize
        }, "interact.enableResizing is deprecated and will soon be removed."), J.enableGesturing = Z(function (t) {
            return null !== t && void 0 !== t ? (kt.gesture = t, J) : kt.gesture
        }, "interact.enableGesturing is deprecated and will soon be removed."), J.eventTypes = _t, J.debug = function () {
            var t = xt[0] || new H;
            return {
                interactions: xt,
                target: t.target,
                dragging: t.dragging,
                resizing: t.resizing,
                gesturing: t.gesturing,
                prepared: t.prepared,
                matches: t.matches,
                matchElements: t.matchElements,
                prevCoords: t.prevCoords,
                startCoords: t.startCoords,
                pointerIds: t.pointerIds,
                pointers: t.pointers,
                addPointer: Wt.addPointer,
                removePointer: Wt.removePointer,
                recordPointer: Wt.recordPointer,
                snap: t.snapStatus,
                restrict: t.restrictStatus,
                inertia: t.inertiaStatus,
                downTime: t.downTimes[0],
                downEvent: t.downEvent,
                downPointer: t.downPointer,
                prevEvent: t.prevEvent,
                Interactable: Q,
                interactables: yt,
                pointerIsDown: t.pointerIsDown,
                defaultOptions: bt,
                defaultActionChecker: L,
                actionCursors: At,
                dragMove: Wt.dragMove,
                resizeMove: Wt.resizeMove,
                gestureMove: Wt.gestureMove,
                pointerUp: Wt.pointerUp,
                pointerDown: Wt.pointerDown,
                pointerMove: Wt.pointerMove,
                pointerHover: Wt.pointerHover,
                eventTypes: _t,
                events: qt,
                globalEvents: Xt,
                delegatedEvents: St,
                prefixedPropREs: Ht
            }
        }, J.getPointerAverage = D, J.getTouchBBox = T, J.getTouchDistance = C, J.getTouchAngle = M, J.getElementRect = w, J.getElementClientRect = b, J.matchesSelector = rt, J.closest = O, J.margin = Z(function (t) {
            return a(t) ? (Tt = t, J) : Tt
        }, "interact.margin is deprecated. Use interact(target).resizable({ margin: number }); instead."), J.supportsTouch = function () {
            return zt
        }, J.supportsPointerEvent = function () {
            return Dt
        }, J.stop = function (t) {
            for (var e = xt.length - 1; e >= 0; e--)xt[e].stop(t);
            return J
        }, J.dynamicDrop = function (t) {
            return h(t) ? (Et = t, J) : Et
        }, J.pointerMoveTolerance = function (t) {
            return a(t) ? (Ct = t, this) : Ct
        }, J.maxInteractions = function (t) {
            return a(t) ? (Pt = t, this) : Pt
        }, J.createSnapGrid = function (t) {
            return function (e, i) {
                var r = 0, s = 0;
                return n(t.offset) && (r = t.offset.x, s = t.offset.y), {x: Math.round((e - r) / t.x) * t.x + r, y: Math.round((i - s) / t.y) * t.y + s, range: t.range}
            }
        }, et(ht), Rt in Element.prototype && o(Element.prototype[Rt]) || (ot = function (t, e, i) {
            i = i || t.parentNode.querySelectorAll(e), e = 0;
            for (var r = i.length; r > e; e++)if (i[e] === t)return !0;
            return !1
        }), function () {
            for (var e = 0, i = ["ms", "moz", "webkit", "o"], r = 0; r < i.length && !t.requestAnimationFrame; ++r)Ft = t[i[r] + "RequestAnimationFrame"], Nt = t[i[r] + "CancelAnimationFrame"] || t[i[r] + "CancelRequestAnimationFrame"];
            Ft || (Ft = function (t) {
                var i = (new Date).getTime(), r = Math.max(0, 16 - (i - e)), s = setTimeout(function () {
                    t(i + r)
                }, r);
                return e = i + r, s
            }), Nt || (Nt = function (t) {
                clearTimeout(t)
            })
        }(), t.interact = J
    }
}("undefined" == typeof window ? void 0 : window);