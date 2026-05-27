(function(){
    function getCsrfToken(){
        var m = document.querySelector('meta[name="csrf-token"]');
        return m ? m.getAttribute('content') : null;
    }

    async function sendRequest(url, method, body){
        var headers = {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': getCsrfToken()
        };

        var opts = { method: method, headers: headers };

        if (body instanceof FormData) {
            opts.body = body;
        } else if (body) {
            headers['Content-Type'] = 'application/json';
            opts.body = JSON.stringify(body);
        }

        var res = await fetch(url, opts);
        return res;
    }

    function findMethodFromForm(form){
        var m = form.querySelector('input[name="_method"]');
        return m ? (m.value || 'POST') : (form.method || 'POST');
    }

    // apply DOM updates based on a JSON payload
    function applyJsonUpdate(json){
        if (!json) return false;
        if (json.redirect) { window.location.href = json.redirect; return true; }

        // deleted
        if (json.action === 'deleted') {
            var tr = document.querySelector('tr[data-id="' + (json.record && json.record.id) + '"]');
            if (tr) { tr.remove(); }
            return true;
        }

        if (!json.record || !json.model) return false;

        // student
        if (json.model === 'student') {
            var r = json.record;
            var tr = document.querySelector('tr[data-id="' + r.id + '"]');
            if (tr) {
                tr.dataset.username = r.username || '';
                tr.dataset.email = r.Email || '';
                tr.dataset.fname = r.Fname || '';
                tr.dataset.mname = r.Mname || '';
                tr.dataset.lname = r.Lname || '';
                tr.dataset.address = r.Address || '';
                tr.dataset.contactno = r.Contactno || '';
                tr.dataset.degree = r.degree_name || '';
                if (tr.cells[0]) tr.cells[0].textContent = r.username || tr.cells[0].textContent;
                if (tr.cells[1]) tr.cells[1].textContent = r.Email || tr.cells[1].textContent;
                if (tr.cells[2]) tr.cells[2].textContent = r.Fname || tr.cells[2].textContent;
                if (tr.cells[3]) tr.cells[3].textContent = r.Lname || tr.cells[3].textContent;
                if (tr.cells[4]) tr.cells[4].textContent = r.degree_name || tr.cells[4].textContent;
                if (tr.cells[5]) tr.cells[5].textContent = r.created_at || tr.cells[5].textContent;
            } else if (json.action === 'created') {
                return false; // caller may choose to reload or add row
            }
            return true;
        }

        // degree
        if (json.model === 'degree') {
            var d = json.record;
            var tr = document.querySelector('tr[data-id="' + d.id + '"]');
            if (tr) {
                tr.dataset.name = d.name || '';
                if (tr.cells[1]) tr.cells[1].textContent = d.name || tr.cells[1].textContent;
            } else if (json.action === 'created') {
                return false;
            }
            return true;
        }

        // teacher
        if (json.model === 'teacher') {
            var t = json.record;
            var tr = document.querySelector('tr[data-id="' + t.id + '"]');
            if (tr) {
                tr.dataset.username = t.username || '';
                tr.dataset.email = t.email || '';
                if (tr.cells[0]) tr.cells[0].textContent = t.username || tr.cells[0].textContent;
                if (tr.cells[1]) tr.cells[1].textContent = t.email || tr.cells[1].textContent;
                if (tr.cells[2]) tr.cells[2].textContent = t.created_at || tr.cells[2].textContent;
            } else if (json.action === 'created') {
                return false;
            }
            return true;
        }

        return false;
    }

    // broadcast a change so other tabs can react
    function broadcastJsonUpdate(json){
        try {
            var payload = { ts: Date.now(), sender: _jmmSenderId, data: json };
            // record outbound ts so we can ignore echoes in this tab
            try { _jmmLastOutboundTs = payload.ts; } catch (e) {}
            localStorage.setItem('jmm_ajax_event', JSON.stringify(payload));
            try {
                if (_jmmChannel) _jmmChannel.postMessage(payload);
            } catch (e) { /* ignore */ }
        } catch (e) { /* ignore */ }
    }

    // unique id for this tab/window to avoid reacting to our own broadcasts
    var _jmmSenderId = ('jmm_' + Math.random().toString(36).slice(2) + Date.now());
    var _jmmLastOutboundTs = null;

    // BroadcastChannel fallback for more reliable same-origin messaging
    var _jmmChannel = null;
    try {
        if (typeof BroadcastChannel !== 'undefined') {
            _jmmChannel = new BroadcastChannel('jmm_channel');
            _jmmChannel.onmessage = function(ev){
                try {
                    console.debug('[jmm] channel message', ev.data);
                    var payload = ev.data || null;
                    // compatibility: if a plain model object was sent, wrap it
                    if (payload && !payload.data && payload.model) payload = { ts: Date.now(), sender: null, data: payload };
                    if (!payload || !payload.data) return;
                    if ((payload.sender && payload.sender === _jmmSenderId) || (payload.ts && payload.ts === _jmmLastOutboundTs)) return; // ignore our own messages/echoes

                    var ok = applyJsonUpdate(payload.data);
                    if (!ok && payload.data.action === 'created') addRowForModel(payload.data);

                    // reload teacher management page when a teacher changed in another tab
                    try {
                        var locPath = window.location.pathname.replace(/\/$/, '');
                        if (payload.data && payload.data.model === 'teacher' && locPath === '/teacher/management') {
                            window.location.reload();
                        }
                    } catch (e) { /* ignore */ }
                } catch(e){ console.error('[jmm] channel handler', e); }
            };
        }
    } catch (ex) { /* ignore */ }

    // try to insert a new row for created records in other tabs
    function addRowForModel(json){
        if (!json || !json.model || !json.record) return false;
        var model = json.model;
        var r = json.record;

        // helper to clone actions from a sample row
        function cloneActionsCell(tbody){
            var sample = tbody.querySelector('tr');
            if (!sample) return '';
            var actionsCell = sample.querySelector('td:last-child');
            return actionsCell ? actionsCell.innerHTML : '';
        }

        // find table based on headers heuristic
        var tables = document.querySelectorAll('table');
        var targetTbody = null;
        var matchTable = null;

        for (var i=0;i<tables.length;i++){
            var ths = Array.from(tables[i].querySelectorAll('th')).map(function(x){return x.textContent.trim().toLowerCase();});
            if (model === 'student') {
                if (ths.indexOf('username')!==-1 && ths.indexOf('first name')!==-1 && ths.indexOf('degree')!==-1) { matchTable = tables[i]; break; }
            }
            if (model === 'teacher') {
                if (ths.indexOf('username')!==-1 && ths.indexOf('email')!==-1 && ths.indexOf('created date')!==-1) { matchTable = tables[i]; break; }
            }
            if (model === 'degree') {
                if (ths.indexOf('degree')!==-1 || ths.indexOf('name')!==-1) { matchTable = tables[i]; break; }
            }
        }

        if (!matchTable) return false;
        targetTbody = matchTable.querySelector('tbody') || matchTable;

        // build row for known models
        var tr = document.createElement('tr');
        tr.setAttribute('data-id', (r.id || ''));

        if (model === 'student'){
            tr.innerHTML = '<td>' + (r.username||'') + '</td>' +
                           '<td>' + (r.Email||'') + '</td>' +
                           '<td>' + (r.Fname||'') + '</td>' +
                           '<td>' + (r.Lname||'') + '</td>' +
                           '<td>' + (r.degree_name||'') + '</td>' +
                           '<td>' + (r.created_at||'') + '</td>' +
                           '<td>' + cloneActionsCell(targetTbody) + '</td>';
        } else if (model === 'teacher'){
            tr.innerHTML = '<td>' + (r.username||'') + '</td>' +
                           '<td>' + (r.email||'') + '</td>' +
                           '<td>' + (r.created_at||'') + '</td>' +
                           '<td>' + cloneActionsCell(targetTbody) + '</td>';
        } else if (model === 'degree'){
            tr.innerHTML = '<td></td>' +
                           '<td>' + (r.name||'') + '</td>' +
                           '<td>' + cloneActionsCell(targetTbody) + '</td>';
        } else {
            return false;
        }

        targetTbody.appendChild(tr);
        return true;
    }

    // listen for updates from other tabs
    window.addEventListener('storage', function(e){
        if (!e || !e.key) return;
        if (e.key !== 'jmm_ajax_event') return;
        if (!e.newValue) return;
        try {
            console.debug('[jmm] storage event received', e.newValue);
            var payload = JSON.parse(e.newValue);
            if (payload && payload.data) {
                console.debug('[jmm] applying payload', payload.data);
                // ignore payloads from this tab (by sender or by matching outbound ts)
                if ((payload.sender && payload.sender === _jmmSenderId) || (payload.ts && payload.ts === _jmmLastOutboundTs)) return;
                var ok = applyJsonUpdate(payload.data);
                if (!ok && payload.data.action === 'created') {
                    // try to add a new row for created records
                    if (addRowForModel(payload.data)) { console.debug('[jmm] added row for created record'); }
                }
                // reload teacher management page when a teacher changed in another tab
                try {
                    var locPath2 = window.location.pathname.replace(/\/$/, '');
                    if (payload.data && payload.data.model === 'teacher' && locPath2 === '/teacher/management') {
                        window.location.reload();
                    }
                } catch (e) { /* ignore */ }
            }
        } catch (ex) { console.error('[jmm] storage handler parse error', ex); }
    }, false);

    document.addEventListener('click', function(e){
        var el = e.target.closest('[data-ajax-delete]');
        if (!el) return;
        e.preventDefault();

        var url = el.getAttribute('href') || el.getAttribute('data-url');
        if (!url) return;
        var method = (el.getAttribute('data-method') || 'DELETE').toUpperCase();

        if (!confirm('Are you sure you want to delete this item?')) return;

        sendRequest(url, method).then(function(res){
            if (res.ok) {
                res.text().then(function(t){
                    try {
                        var json = t ? JSON.parse(t) : null;
                        if (json) {
                            if (applyJsonUpdate(json)) { broadcastJsonUpdate(json); return; }
                            try { broadcastJsonUpdate(json); } catch (e) {}
                        }
                    } catch (ex) {
                        // not JSON or parse failed
                    }
                    var form = el.closest('form');
                    var tr = el.closest('tr') || (form ? form.closest('tr') : null);
                    var payload = { action: 'deleted', model: (el.getAttribute('data-model') || (form && form.getAttribute('data-model')) || null), record: { id: tr ? tr.dataset.id || null : null } };
                    try { broadcastJsonUpdate(payload); } catch (e) {}
                    if (tr) tr.remove(); else location.reload();
                }).catch(function(){
                    var form = el.closest('form');
                    var tr = el.closest('tr') || (form ? form.closest('tr') : null);
                    var payload = { action: 'deleted', model: (el.getAttribute('data-model') || (form && form.getAttribute('data-model')) || null), record: { id: tr ? tr.dataset.id || null : null } };
                    try { broadcastJsonUpdate(payload); } catch (e) {}
                    if (tr) tr.remove(); else location.reload();
                });
            } else res.text().then(t=>alert('Delete failed: '+t));
        }).catch(function(err){ alert('Request failed'); });
    }, false);

    // handle delete forms (class ajax-delete)
    document.addEventListener('submit', function(e){
        var form = e.target.closest('form.ajax-delete');
        if (!form) return;
        e.preventDefault();

        if (!confirm('Are you sure you want to delete this item?')) return;

        var action = form.getAttribute('action');
        var method = findMethodFromForm(form).toUpperCase();
        var fd = new FormData(form);

        sendRequest(action, method, fd).then(function(res){
            if (res.ok) {
                res.text().then(function(t){
                    try {
                        var json = t ? JSON.parse(t) : null;
                        if (json) {
                            if (applyJsonUpdate(json)) { broadcastJsonUpdate(json); return; }
                            try { broadcastJsonUpdate(json); } catch (e) {}
                        }
                    } catch (ex) {}
                    // If server did not return JSON, broadcast a delete payload so other tabs update
                    try {
                        var tr = form.closest('tr');
                        var payload = { action: 'deleted', model: (form.getAttribute('data-model') || null), record: { id: tr ? tr.dataset.id || null : null } };
                        broadcastJsonUpdate(payload);
                    } catch (e) {}
                    location.reload();
                }).catch(function(){ location.reload(); });
            } else {
                res.text().then(t=>alert('Delete failed: '+t));
            }
        }).catch(function(){ alert('Request failed'); });
    }, false);

    // handle generic ajax submits (class ajax-submit)
    document.addEventListener('submit', function(e){
        var form = e.target.closest('form.ajax-submit');
        if (!form) return;
        e.preventDefault();

        var action = form.getAttribute('action') || window.location.href;
        var method = findMethodFromForm(form).toUpperCase();
        var fd = new FormData(form);

        sendRequest(action, method, fd).then(function(res){
            if (res.ok) {
                res.text().then(function(t){
                    try {
                        var json = t ? JSON.parse(t) : null;
                        if (json) {
                            if (applyJsonUpdate(json)) { broadcastJsonUpdate(json); return; }
                            try { broadcastJsonUpdate(json); } catch (e) {}
                        }
                    } catch (ex) {}
                    location.reload();
                }).catch(function(){ location.reload(); });
            } else {
                res.text().then(t=>alert('Request failed: '+t));
            }
        }).catch(function(){ alert('Request failed'); });
    }, false);
})();
