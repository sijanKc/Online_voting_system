/**
 * Location Cascade System
 * Handles dynamic fetching of districts and constituencies based on user selection.
 */

class LocationCascade {
    constructor(config) {
        this.provinceSelect = document.getElementById(config.provinceId);
        this.districtSelect = document.getElementById(config.districtId);
        this.constituencySelect = document.getElementById(config.constituencyId);

        this.init();
    }

    init() {
        if (this.provinceSelect) {
            this.provinceSelect.addEventListener('change', (e) => this.loadDistricts(e.target.value));
        }

        if (this.districtSelect) {
            this.districtSelect.addEventListener('change', (e) => this.loadConstituencies(e.target.value));
        }
    }

    async loadDistricts(provinceId) {
        // Reset dependent dropdowns
        this.resetSelect(this.districtSelect, 'Select District');
        this.resetSelect(this.constituencySelect, 'Select Constituency');

        if (!provinceId) return;

        this.setLoading(this.districtSelect, true);

        try {
            const response = await fetch(`api/get_districts.php?province_id=${provinceId}`);
            const data = await response.json();
            this.populateSelect(this.districtSelect, data);
        } catch (error) {
            console.error('Error loading districts:', error);
            alert('Failed to load districts. Please try again.');
        } finally {
            this.setLoading(this.districtSelect, false);
        }
    }

    async loadConstituencies(districtId) {
        // Reset dependent dropdown
        this.resetSelect(this.constituencySelect, 'Select Constituency');

        if (!districtId) return;

        this.setLoading(this.constituencySelect, true);

        try {
            const response = await fetch(`api/get_constituencies.php?district_id=${districtId}`);
            const data = await response.json();
            this.populateSelect(this.constituencySelect, data);
        } catch (error) {
            console.error('Error loading constituencies:', error);
        } finally {
            this.setLoading(this.constituencySelect, false);
        }
    }

    resetSelect(selectElement, defaultText) {
        selectElement.innerHTML = `<option value="">${defaultText}</option>`;
        selectElement.disabled = true;
    }

    populateSelect(selectElement, data) {
        if (data.length > 0) {
            data.forEach(item => {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.name;
                selectElement.appendChild(option);
            });
            selectElement.disabled = false;
        }
    }

    setLoading(selectElement, isLoading) {
        if (isLoading) {
            const loadingOption = document.createElement('option');
            loadingOption.text = 'Loading...';
            selectElement.add(loadingOption, 0);
            selectElement.selectedIndex = 0;
            selectElement.disabled = true;
        } else {
            // Remove loading option if exists
            if (selectElement.options[0].text === 'Loading...') {
                selectElement.remove(0);
            }
        }
    }
}

/**
 * Party Selection Handler
 * Handles logic for showing/hiding party logo upload based on selection
 */
/**
 * Party Selection Handler
 * Handles logic for showing/hiding party logo or auto-displaying it
 */
function handlePartySelection(partySelectId, wrapperId) {
    const partySelect = document.getElementById(partySelectId);
    const wrapper = document.getElementById('party_logo_wrapper'); // Hardcoded ID from PHP
    const previewImg = document.getElementById('logo_preview');
    const msg = document.getElementById('party_msg');
    const uploadBox = document.getElementById('independent_upload_box');

    // Also attach the global preview function for custom uploads
    window.previewCustomLogo = function (input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                previewImg.src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    };

    if (!partySelect) return;

    partySelect.addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        const logoPath = selectedOption.dataset.logo;
        const partyName = selectedOption.dataset.name || '';
        const isIndependent = partyName.toLowerCase().includes('independent') || partyName.includes('स्वतन्त्र');

        if (this.value) {
            // Show wrapper
            if (wrapper) wrapper.style.display = 'block';

            // Set Logo Source
            // If Independent, use what's in logo_path (placeholder) OR default if empty
            if (logoPath && logoPath !== 'null') {
                previewImg.src = logoPath;
            } else {
                // Fallback if no logo path
                previewImg.src = 'assets/images/no-logo.png';
            }

            // Message
            if (msg) msg.textContent = isIndependent
                ? "You have selected Independent. You may keep the default symbol or upload your own."
                : `${partyName} Election Symbol`;

            // Toggle Upload Box
            if (uploadBox) {
                uploadBox.style.display = isIndependent ? 'block' : 'none';

                // If not independent, clear any file input to avoid accidental upload
                if (!isIndependent) {
                    const input = document.getElementById('party_logo_input');
                    if (input) input.value = '';
                }
            }

        } else {
            if (wrapper) wrapper.style.display = 'none';
        }
    });
}
