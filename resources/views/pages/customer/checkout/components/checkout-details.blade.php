{{--
    ERP MODULE: Checkout — Shipping & Contact Details (Checkout Page)
    COMPONENT: Checkout Details Form
    DESCRIPTION: Contact info + shipping address form. Includes sub-components: contact-fields, address-section, order-notes. Inline JS for address modal/card interactions.
    DATA SOURCE: $cart, $summary, $addresses from CheckoutController@index
    ROUTE: POST /checkout (CheckoutController@store)
    TODO: Extract JS to separate file and convert POST to JSON REST API
--}}

<div class="lg:col-span-2 bg-white border border-gray-200 rounded-2xl p-5 shadow-sm h-fit">
    <div class="flex items-center gap-2 mb-5">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="1" y="3" width="15" height="13"></rect>
            <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
            <circle cx="5.5" cy="18.5" r="2.5"></circle>
            <circle cx="18.5" cy="18.5" r="2.5"></circle>
        </svg>
        <h2 class="text-sm font-semibold text-gray-900">Checkout Details</h2>
    </div>

    <form method="POST" action="{{ route('checkout.store') }}" class="space-y-4">
        @csrf

        @include('pages.customer.checkout.components.contact-fields')

        <div>
            <label class="block text-xs font-medium text-gray-600 mb-2">Shipping Address</label>
            @include('pages.customer.checkout.components.address-section')
        </div>

        @include('pages.customer.checkout.components.order-notes')
    </form>
</div>

{{-- Pending: JS will be extracted to its own file when REST API is implemented --}}
<script>
    document.querySelectorAll('.address-card').forEach(card => {
        const editBtn = card.querySelector('.edit-address-btn');
        if (editBtn) {
            editBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                openAddressModal(true, {
                    address_id: card.querySelector('input[type=radio]').value,
                    address_type: card.dataset.type,
                    street: card.dataset.street,
                    barangay: card.dataset.barangay,
                    city: card.dataset.city,
                    province: card.dataset.province,
                    postal_code: card.dataset.postal,
                    country: card.dataset.country,
                });
            });
        }

        card.addEventListener('click', function() {
            document.querySelectorAll('.address-card').forEach(c => {
                c.classList.remove('border-cyan-500', 'bg-cyan-50/40');
                c.classList.add('border-gray-200');
                c.querySelector('.w-2\\.5').classList.remove('bg-cyan-500');
                c.querySelector('.w-5').classList.remove('border-cyan-500');
                c.querySelector('.w-5').classList.add('border-gray-300');
            });
            this.classList.remove('border-gray-200');
            this.classList.add('border-cyan-500', 'bg-cyan-50/40');
            this.querySelector('.w-2\\.5').classList.add('bg-cyan-500');
            this.querySelector('.w-5').classList.remove('border-gray-300');
            this.querySelector('.w-5').classList.add('border-cyan-500');
            this.querySelector('input[type=radio]').checked = true;

            fillAddressFields(this.dataset.street, this.dataset.barangay, this.dataset.city, this.dataset.province, this.dataset.postal, this.dataset.country, this.dataset.type);
            document.getElementById('addressFields').classList.remove('hidden');
        });
    });

    function fillAddressFields(street, barangay, city, province, postal, country, type) {
        document.getElementById('street').value = street;
        document.getElementById('barangay').value = barangay;
        document.getElementById('city').value = city;
        document.getElementById('province').value = province;
        document.getElementById('postal_code').value = postal;
        document.getElementById('country').value = country;
        if (type) document.getElementById('address_type').value = type;
    }

    function openAddressModal(clear, addr) {
        if (clear && addr) {
            document.getElementById('modal_address_id').value = addr.address_id || '';
            document.getElementById('modal_type').value = addr.address_type || 'Home';
            document.getElementById('modal_street').value = addr.street || '';
            document.getElementById('modal_barangay').value = addr.barangay || '';
            document.getElementById('modal_city').value = addr.city || '';
            document.getElementById('modal_province').value = addr.province || '';
            document.getElementById('modal_postal').value = addr.postal_code || '';
            document.getElementById('modal_country').value = addr.country || '';
        } else if (clear) {
            ['modal_address_id','modal_street','modal_barangay','modal_city','modal_province','modal_postal','modal_country'].forEach(id => document.getElementById(id).value = '');
            document.getElementById('modal_type').value = 'Home';
        } else {
            document.getElementById('modal_street').value = document.getElementById('street').value;
            document.getElementById('modal_barangay').value = document.getElementById('barangay').value;
            document.getElementById('modal_city').value = document.getElementById('city').value;
            document.getElementById('modal_province').value = document.getElementById('province').value;
            document.getElementById('modal_postal').value = document.getElementById('postal_code').value;
            document.getElementById('modal_country').value = document.getElementById('country').value;
            document.getElementById('modal_type').value = document.getElementById('address_type').value || 'Home';
        }
        document.getElementById('addressModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeAddressModal() {
        document.getElementById('addressModal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    function esc(str) {
        const d = document.createElement('div');
        d.textContent = str;
        return d.innerHTML;
    }

    function updateAddressCard(card, addr) {
        card.setAttribute('data-street', addr.street);
        card.setAttribute('data-barangay', addr.barangay);
        card.setAttribute('data-city', addr.city);
        card.setAttribute('data-province', addr.province);
        card.setAttribute('data-postal', addr.postal_code);
        card.setAttribute('data-country', addr.country);
        card.setAttribute('data-type', addr.address_type);

        card.querySelector('input[type=radio]').value = addr.address_id;
        const typeSpan = card.querySelector('.text-gray-400.uppercase');
        if (typeSpan) typeSpan.textContent = addr.address_type;
        const lines = card.querySelectorAll('.text-xs.text-gray-500');
        if (lines[0]) lines[0].textContent = addr.street + ', ' + addr.barangay;
        if (lines[1]) lines[1].textContent = addr.city + ', ' + addr.province + ' ' + addr.postal_code;
    }

    function useAddressFromModal() {
        const street = document.getElementById('modal_street').value.trim();
        const barangay = document.getElementById('modal_barangay').value.trim();
        if (!street || !barangay) {
            toastNotify('error', 'Please fill in at least the Street and Barangay fields.');
            return;
        }

        const payload = {
            address_id: document.getElementById('modal_address_id').value || null,
            address_type: document.getElementById('modal_type').value,
            street: street,
            barangay: barangay,
            city: document.getElementById('modal_city').value.trim(),
            province: document.getElementById('modal_province').value.trim(),
            postal_code: document.getElementById('modal_postal').value.trim(),
            country: document.getElementById('modal_country').value.trim(),
        };

        fetch('{{ route('checkout.address.save') }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify(payload),
        })
        .then(r => r.json())
        .then(data => {
            const addr = data.address;
            fillAddressFields(addr.street, addr.barangay, addr.city, addr.province, addr.postal_code, addr.country, addr.address_type);
            document.getElementById('addressFields').classList.remove('hidden');
            closeAddressModal();

            const existingCard = document.querySelector('.address-card input[type=radio][value="' + addr.address_id + '"]');
            if (existingCard) {
                updateAddressCard(existingCard.closest('.address-card'), addr);
                existingCard.closest('.address-card').click();
            } else {
                addAddressCard(addr);
            }
            toastNotify('success', 'Address saved!');
        })
        .catch(() => toastNotify('error', 'Failed to save address.'));
    }

    function addAddressCard(addr) {
        let wrap = document.getElementById('savedAddressCards');
        if (!wrap) {
            wrap = document.createElement('div');
            wrap.id = 'savedAddressCards';
            wrap.className = 'space-y-3 mb-4';
            document.getElementById('addAddressBtn').insertAdjacentElement('beforebegin', wrap);
        }

        const card = document.createElement('label');
        card.className = 'address-card block border border-gray-200 rounded-xl p-4 cursor-pointer transition-colors hover:border-cyan-300 border-cyan-500 bg-cyan-50/40';
        card.setAttribute('data-street', addr.street);
        card.setAttribute('data-barangay', addr.barangay);
        card.setAttribute('data-city', addr.city);
        card.setAttribute('data-province', addr.province);
        card.setAttribute('data-postal', addr.postal_code);
        card.setAttribute('data-country', addr.country);
        card.setAttribute('data-type', addr.address_type);

        const isDefault = addr.is_default;
        card.innerHTML = `
            <input type="radio" name="selected_address" value="${addr.address_id}" class="hidden" ${isDefault ? 'checked' : ''}>
            <div class="flex items-start justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        ${isDefault ? '<span class="text-[10px] font-semibold bg-cyan-100 text-cyan-700 px-2 py-0.5 rounded-full">DEFAULT</span>' : ''}
                        <span class="text-[10px] font-medium text-gray-400 uppercase">${esc(addr.address_type)}</span>
                    </div>
                    <p class="text-xs text-gray-500">${esc(addr.street)}, ${esc(addr.barangay)}</p>
                    <p class="text-xs text-gray-500">${esc(addr.city)}, ${esc(addr.province)} ${esc(addr.postal_code)}</p>
                </div>
                <div class="flex flex-col items-end gap-1 shrink-0 mt-1">
                    <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center ${isDefault ? 'border-cyan-500' : 'border-gray-300'}">
                        <div class="w-2.5 h-2.5 rounded-full ${isDefault ? 'bg-cyan-500' : ''}"></div>
                    </div>
                    <button type="button" class="edit-address-btn text-gray-300 hover:text-cyan-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                    </button>
                </div>
            </div>
        `;

        card.querySelector('.edit-address-btn').addEventListener('click', function(e) {
            e.stopPropagation();
            openAddressModal(true, addr);
        });

        wrap.prepend(card);

        card.addEventListener('click', function() {
            document.querySelectorAll('.address-card').forEach(c => {
                c.classList.remove('border-cyan-500', 'bg-cyan-50/40');
                c.classList.add('border-gray-200');
                const dot = c.querySelector('.w-2\\.5');
                const ring = c.querySelector('.w-5');
                if (dot) dot.classList.remove('bg-cyan-500');
                if (ring) { ring.classList.remove('border-cyan-500'); ring.classList.add('border-gray-300'); }
            });
            this.classList.remove('border-gray-200');
            this.classList.add('border-cyan-500', 'bg-cyan-50/40');
            const dot = this.querySelector('.w-2\\.5');
            const ring = this.querySelector('.w-5');
            if (dot) dot.classList.add('bg-cyan-500');
            if (ring) { ring.classList.remove('border-gray-300'); ring.classList.add('border-cyan-500'); }
            this.querySelector('input[type=radio]').checked = true;
            fillAddressFields(this.dataset.street, this.dataset.barangay, this.dataset.city, this.dataset.province, this.dataset.postal, this.dataset.country, this.dataset.type);
            document.getElementById('addressFields').classList.remove('hidden');
        });

        card.click();
    }

    document.getElementById('addressModal').addEventListener('click', function(e) {
        if (e.target === this) closeAddressModal();
    });

    document.addEventListener('DOMContentLoaded', function() {
        const defaultCard = document.querySelector('.address-card input[type=radio]:checked');
        if (defaultCard) {
            defaultCard.closest('.address-card').click();
        }
    });

    function toastNotify(type, message) {
        const container = document.getElementById('toastContainer');
        if (!container) return;
        const colors = { success: 'bg-green-500', error: 'bg-red-500', info: 'bg-blue-500' };
        const toast = document.createElement('div');
        toast.className = `${colors[type] || 'bg-gray-700'} text-white text-xs px-4 py-2.5 rounded-lg shadow-lg opacity-0 transition-opacity duration-300`;
        toast.textContent = message;
        container.appendChild(toast);
        requestAnimationFrame(() => toast.style.opacity = '1');
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
</script>
