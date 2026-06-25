(function () {
  // ✅ Check if commercial flow is enabled
  const isCommercialFlow = window.__PROGRAMME__?.hasCommercialFlow || false;

  // ✅ Only run commercial flow logic if enabled
  if (!isCommercialFlow) {
    console.log('Participant-only mode - commercial features disabled');
    return; // Exit early for participant-only mode
  }

  // === COMMERCIAL FLOW ONLY ===
  const packageSelect = document.querySelector('[data-package-select]');
  const qtyInput = document.querySelector('[data-qty]');
  const totalEl = document.querySelector('[data-total]');
  const participantsWrap = document.querySelector('[data-participants-wrap]');
  const expectedEl = document.querySelector('[data-expected-count]');

  const paymentSelect = document.querySelector('[data-payment-select]');
  const paymentInfoBox = document.querySelector('[data-payment-info]');
  const paymentBank = document.querySelector('[data-payment-bank]');
  const paymentAcc = document.querySelector('[data-payment-acc]');
  const paymentName = document.querySelector('[data-payment-name]');

  // payment methods dataset
  const paymentMap = window.__PAYMENT_MAP__ || {};
  // packages dataset
  const packageMap = window.__PACKAGE_MAP__ || {};

  function getSelectedPackage() {
    const id = packageSelect ? packageSelect.value : '';
    return id && packageMap[id] ? packageMap[id] : null;
  }

  function computeExpected(pkg, qty) {
    if (!pkg) return 0;
    qty = Math.max(1, parseInt(qty || '1', 10));
    if (pkg.package_type === 'multi_person') {
      const ppl = Math.max(1, parseInt(pkg.people_per_package || '1', 10));
      return qty * ppl;
    }
    return qty;
  }

  function setTotal(pkg, qty) {
    if (!totalEl) return;
    const q = Math.max(1, parseInt(qty || '1', 10));
    const price = pkg ? parseFloat(pkg.price || '0') : 0;
    const total = (price * q).toFixed(2);
    totalEl.textContent = `RM ${total}`;
  }

  function buildParticipantInputs(count) {
    if (!participantsWrap) return;

    // Keep current values if possible
    const existing = Array.from(participantsWrap.querySelectorAll('[data-participant-item]')).map(item => {
      const name = item.querySelector('input[name$="[name]"]')?.value || '';
      const position = item.querySelector('input[name$="[position]"]')?.value || '';
      return { name, position };
    });

    participantsWrap.innerHTML = '';

    // ✅ Show placeholder if no participants expected
    if (count === 0) {
      participantsWrap.innerHTML = `
        <div class="pp-participant-placeholder" style="text-align:center;padding:40px 20px;color:#9ca3af;">
          <div style="font-size:48px;margin-bottom:16px;">👥</div>
          <h4 style="margin:0 0 8px 0;color:#6b7280;">No participants yet</h4>
          <p style="margin:0;font-size:14px;">Select a package above to add participants</p>
        </div>
      `;
      return;
    }

    for (let i = 0; i < count; i++) {
      const saved = existing[i] || { name: '', position: '' };

      const div = document.createElement('div');
      div.className = 'pp-participant-row';
      div.setAttribute('data-participant-item', '1');

      div.innerHTML = `
        <div>
          <div class="pp-row-title">Participant ${i + 1}</div>
          <div class="pp-field">
            <div class="pp-label required">Name</div>
            <input class="pp-input uppercase-field" type="text" name="participants[${i}][name]" value="${escapeHtml(saved.name)}" required placeholder="ENTER FULL NAME">
          </div>
        </div>
        <div>
          <div class="pp-row-title">&nbsp;</div>
          <div class="pp-field">
            <div class="pp-label required">Position</div>
            <input class="pp-input uppercase-field" type="text" name="participants[${i}][position]" value="${escapeHtml(saved.position)}" required placeholder="ENTER POSITION">
          </div>
        </div>
      `;
      participantsWrap.appendChild(div);
    }

    // ✅ Re-attach uppercase handlers to new inputs
    attachUppercaseHandlers();
  }

  function escapeHtml(str) {
    return String(str || '')
      .replaceAll('&', '&amp;')
      .replaceAll('<', '&lt;')
      .replaceAll('>', '&gt;')
      .replaceAll('"', '&quot;')
      .replaceAll("'", '&#039;');
  }

  // ✅ Attach uppercase handlers to dynamically created inputs
  function attachUppercaseHandlers() {
    const uppercaseFields = participantsWrap.querySelectorAll('.uppercase-field');
    uppercaseFields.forEach(field => {
      field.removeEventListener('input', uppercaseHandler);
      field.addEventListener('input', uppercaseHandler);
    });
  }

  function uppercaseHandler(e) {
    const cursorPos = e.target.selectionStart;
    e.target.value = (e.target.value || '').toUpperCase();
    e.target.setSelectionRange(cursorPos, cursorPos);
  }

  function updateAll() {
    const pkg = getSelectedPackage();
    const qty = qtyInput ? qtyInput.value : 1;

    const expected = computeExpected(pkg, qty);
    if (expectedEl) expectedEl.textContent = expected ? String(expected) : '-';

    setTotal(pkg, qty);

    // Build participants based on expected count
    if (expected > 0) buildParticipantInputs(expected);
    else if (participantsWrap) {
      participantsWrap.innerHTML = `
        <div class="pp-participant-placeholder" style="text-align:center;padding:40px 20px;color:#9ca3af;">
          <div style="font-size:48px;margin-bottom:16px;">👥</div>
          <h4 style="margin:0 0 8px 0;color:#6b7280;">No participants yet</h4>
          <p style="margin:0;font-size:14px;">Select a package above to add participants</p>
        </div>
      `;
    }
  }

  // payment dropdown -> show details
  function updatePaymentInfo() {
    if (!paymentSelect || !paymentInfoBox) return;

    const id = paymentSelect.value;
    const info = id && paymentMap[id] ? paymentMap[id] : null;

    if (!info) {
      paymentInfoBox.style.display = 'none';
      return;
    }

    paymentInfoBox.style.display = 'block';
    if (paymentBank) paymentBank.value = info.bank || '-';
    if (paymentAcc) paymentAcc.value = info.account_number || '-';
    if (paymentName) paymentName.value = info.account_name || '-';
  }

  if (packageSelect) packageSelect.addEventListener('change', updateAll);
  if (qtyInput) qtyInput.addEventListener('input', updateAll);

  if (paymentSelect) paymentSelect.addEventListener('change', updatePaymentInfo);

  updateAll();
  updatePaymentInfo();
})();

// ✅ PARTICIPANT-ONLY MODE FUNCTIONS (SAME DESIGN AS COMMERCIAL)
function addManualParticipant() {
  const wrap = document.getElementById('participants-manual-wrap');
  if (!wrap) return;

  const existingCount = wrap.querySelectorAll('[data-participant-item]').length;
  const newIndex = existingCount;

  const div = document.createElement('div');
  div.className = 'pp-participant-row';
  div.setAttribute('data-participant-item', '1');

  div.innerHTML = `
    <div>
      <div class="pp-row-title">Participant ${existingCount + 1}</div>
      <div class="pp-field">
        <div class="pp-label required">Name</div>
        <input class="pp-input uppercase-field" 
               type="text" 
               name="participants[${newIndex}][name]" 
               required 
               placeholder="ENTER FULL NAME">
      </div>
    </div>
    <div>
      <div class="pp-row-title">&nbsp;</div>
      <div class="pp-field">
        <div class="pp-label required">Position</div>
        <input class="pp-input uppercase-field" 
               type="text" 
               name="participants[${newIndex}][position]" 
               required 
               placeholder="ENTER POSITION">
      </div>
    </div>
    <div style="display:flex;align-items:flex-end;padding-bottom:8px;">
      <button type="button" 
              class="pp-btn-remove-participant" 
              onclick="removeManualParticipant(this)"
              style="background:#fee2e2;color:#dc2626;border:1px solid #fca5a5;padding:8px 14px;border-radius:4px;cursor:pointer;font-size:16px;font-weight:600;transition:all 0.2s ease;"
              title="Remove participant">×</button>
    </div>
  `;

  wrap.appendChild(div);

  // Attach uppercase handlers to new inputs
  const newInputs = div.querySelectorAll('.uppercase-field');
  newInputs.forEach(input => {
    input.addEventListener('input', function(e) {
      const cursorPos = e.target.selectionStart;
      e.target.value = (e.target.value || '').toUpperCase();
      e.target.setSelectionRange(cursorPos, cursorPos);
    });
  });

  // Update all participant titles
  updateManualParticipantNumbers();
}

function removeManualParticipant(btn) {
  const wrap = document.getElementById('participants-manual-wrap');
  if (!wrap) return;

  const items = wrap.querySelectorAll('[data-participant-item]');
  
  // Keep at least 1 participant
  if (items.length <= 1) {
    alert('At least one participant is required.');
    return;
  }

  // Remove the row
  btn.closest('.pp-participant-row').remove();

  // Update participant numbers and input names
  updateManualParticipantNumbers();
}

function updateManualParticipantNumbers() {
  const wrap = document.getElementById('participants-manual-wrap');
  if (!wrap) return;

  const rows = wrap.querySelectorAll('[data-participant-item]');
  
  rows.forEach((row, index) => {
    // Update title
    const title = row.querySelector('.pp-row-title');
    if (title) {
      title.textContent = `Participant ${index + 1}`;
    }

    // Update input names
    const nameInput = row.querySelector('input[name*="[name]"]');
    const positionInput = row.querySelector('input[name*="[position]"]');

    if (nameInput) nameInput.name = `participants[${index}][name]`;
    if (positionInput) positionInput.name = `participants[${index}][position]`;
  });
}