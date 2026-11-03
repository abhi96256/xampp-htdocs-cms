<!-- Multi-Step Location Wizard Modal -->
<div id="locationWizard" class="wizard-modal">
    <div class="wizard-content">
        <div class="wizard-header">
            <h3>Find Property by City Location</h3>
            <button class="close-wizard" onclick="closeWizard()">&times;</button>
        </div>
        
        <!-- Progress Bar -->
        <div class="wizard-progress">
            <div class="progress-step active" data-step="1">1<span>City</span></div>
            <div class="progress-step" data-step="2">2<span>Category</span></div>
            <div class="progress-step" data-step="3">3<span>Type</span></div>
            <div class="progress-step" data-step="4">4<span>Developer</span></div>
        </div>

        <div class="wizard-body">
            <!-- Step 1: Select City -->
            <div class="wizard-step active" id="step1">
                <h4>Select City Location</h4>
                <div class="wizard-grid" id="cityGrid">
                    <!-- Cities will be loaded here -->
                </div>
            </div>

            <!-- Step 2: Category -->
            <div class="wizard-step" id="step2">
                <h4>Choose Category</h4>
                <div class="wizard-grid" id="categoryGrid">
                    <!-- Categories will be loaded here -->
                </div>
                <button class="wizard-back" onclick="goToStep(1)">Back</button>
            </div>

            <!-- Step 3: Type -->
            <div class="wizard-step" id="step3">
                <h4>Property Type</h4>
                <div class="wizard-grid" id="typeGrid">
                    <!-- Types will be loaded here -->
                </div>
                <button class="wizard-back" onclick="goToStep(2)">Back</button>
            </div>

            <!-- Step 4: Developer -->
            <div class="wizard-step" id="step4">
                <h4>Choose Developer</h4>
                <div class="wizard-grid" id="devGrid">
                    <!-- Developers will be loaded here -->
                </div>
                <button class="wizard-back" onclick="goToStep(3)">Back</button>
            </div>
        </div>
    </div>
</div>

<style>
.wizard-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.85);
    display: none;
    z-index: 99999 !important;
    backdrop-filter: blur(8px);
    align-items: center;
    justify-content: center;
}

.wizard-content {
    background: #fff;
    width: 95%;
    max-width: 800px;
    height: auto;
    max-height: 90vh;
    border-radius: 20px;
    overflow: hidden;
    animation: zoomIn 0.3s ease-out;
    box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5);
    display: flex;
    flex-direction: column;
}

@keyframes zoomIn {
    from { opacity: 0; transform: scale(0.9); }
    to { opacity: 1; transform: scale(1); }
}

.wizard-header {
    background: #c00415;
    color: #fff;
    padding: 20px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.wizard-header h3 { margin: 0; font-size: 22px; font-weight: 700; color: #fff; }
.close-wizard { background: none; border: none; color: #fff; font-size: 30px; cursor: pointer; }

.wizard-progress {
    display: flex;
    background: #f8f9fa;
    padding: 20px;
    border-bottom: 1px solid #eee;
}

.progress-step {
    flex: 1;
    text-align: center;
    font-size: 13px;
    color: #999;
    position: relative;
    font-weight: 600;
}

.progress-step span { display: block; margin-top: 5px; }
.progress-step.active { color: #c00415; }
.progress-step.active::after {
    content: '';
    position: absolute;
    bottom: -20px;
    left: 0;
    width: 100%;
    height: 3px;
    background: #c00415;
}

.wizard-body { 
    padding: 0 !important; 
    flex: 1 !important;
    overflow: hidden !important;
    display: flex;
    flex-direction: column;
    min-height: 0; /* Critical for flex overflow */
}

.wizard-grid::-webkit-scrollbar {
    width: 6px;
}

.wizard-grid::-webkit-scrollbar-track {
    background: #f0f0f0;
}

.wizard-grid::-webkit-scrollbar-thumb {
    background-color: #c00415 !important;
    border-radius: 10px !important;
}
.wizard-step { 
    display: none; 
    flex-direction: column;
    height: 100%;
    padding: 30px;
    box-sizing: border-box;
    min-height: 0; /* Critical for flex overflow */
}
.wizard-step.active { display: flex; }

.wizard-step h4 { 
    flex-shrink: 0;
    margin-top: 0; 
    margin-bottom: 25px; 
    text-align: center; 
    color: #333; 
    font-weight: 700; 
}

.wizard-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 20px;
    flex: 1;
    overflow-y: auto;
    padding: 5px;
    scrollbar-width: thin !important;
    scrollbar-color: #c00415 #f0f0f0 !important;
}

.wizard-card {
    background: #fff;
    border: 2px solid #f0f0f0;
    padding: 25px 15px;
    border-radius: 15px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
}

.wizard-card:hover {
    border-color: #c00415;
    background: #fff5f6;
    transform: translateY(-5px);
    box-shadow: 0 10px 15px -3px rgba(192, 4, 21, 0.1);
}

.wizard-card i { font-size: 35px; color: #c00415; transition: font-size 0.3s; }
.wizard-card span { font-weight: 600; color: #444; font-size: 14px; }

.wizard-back {
    flex-shrink: 0;
    margin-top: 20px !important;
    background: #c00415 !important;
    border: none !important;
    padding: 12px 30px !important;
    border-radius: 8px !important;
    cursor: pointer !important;
    font-weight: 600 !important;
    width: 100% !important;
    color: #ffffff !important;
    transition: all 0.3s ease !important;
    display: block !important;
    box-shadow: 0 4px 15px rgba(192, 4, 21, 0.2) !important;
}

.wizard-back:hover { 
    background: #a00311 !important; 
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 20px rgba(192, 4, 21, 0.3) !important;
}

/* Responsive */
@media (max-width: 600px) {
    .wizard-grid { 
        grid-template-columns: repeat(2, 1fr); 
        gap: 15px; 
        padding: 5px;
    }
    .wizard-card { 
        padding: 20px 10px; 
        border-radius: 12px;
        gap: 8px;
    }
    .wizard-card i { font-size: 28px; }
    .wizard-card span { font-size: 13px; line-height: 1.2; }

    .wizard-step { padding: 15px; flex: 1; display: none; flex-direction: column; min-height: 0; }
    .wizard-step.active { display: flex; }
    .wizard-header { padding: 12px 15px; flex-wrap: nowrap; gap: 8px; }
    .wizard-header h3 { 
        font-size: 15px; 
        line-height: 1.3;
        flex: 1;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .close-wizard { 
        font-size: 24px; 
        flex-shrink: 0;
        line-height: 1;
    }
    .wizard-progress { padding: 10px 8px; }
    .progress-step { font-size: 10px; }
    .progress-step span { font-size: 10px; }
    .wizard-content { 
        width: 95%; 
        height: auto; 
        max-height: 90vh; 
        max-height: 90dvh; 
        border-radius: 14px; 
        padding-bottom: env(safe-area-inset-bottom, 10px);
    }
    .wizard-back { margin-top: 10px; padding: 12px; font-size: 14px; margin-bottom: 5px; }
}

@media (max-width: 380px) {
    .wizard-header h3 { font-size: 13px; }
    .wizard-header { padding: 10px 12px; }
    .close-wizard { font-size: 22px; }
}
</style>

<script>
let selection = {
    city: '',
    category_id: '',
    subcategory_id: '',
    developer: ''
};

// Global Cache to store fetched data
const wizardCache = {
    locations: null,
    categories: {},
    types: {},
    devs: {}
};

// Pre-fetch locations on page load for instant opening
document.addEventListener('DOMContentLoaded', () => {
    fetch(WIZARD_API + '?action=get_locations')
        .then(res => res.json())
        .then(data => { wizardCache.locations = data; })
        .catch(e => console.log('Prefetch failed', e));
});

function openWizard() {
    document.getElementById('locationWizard').style.display = 'flex';
    goToStep(1);
    loadCities();
}

function closeWizard() {
    document.getElementById('locationWizard').style.display = 'none';
}

function goToStep(step) {
    document.querySelectorAll('.wizard-step').forEach(s => s.classList.remove('active'));
    document.getElementById('step' + step).classList.add('active');
    
    document.querySelectorAll('.progress-step').forEach(ps => {
        if(ps.dataset.step <= step) ps.classList.add('active');
        else ps.classList.remove('active');
    });
}

const WIZARD_API = '<?= SITE_URL; ?>get_wizard_data.php';

function renderCities(data) {
    const grid = document.getElementById('cityGrid');
    grid.innerHTML = '';
    if(!data || data.length === 0) {
        grid.innerHTML = '<p class="text-center">No cities found.</p>';
        return;
    }
    data.forEach(item => {
        // Background Pre-fetch Categories
        if(!wizardCache.categories[item.location]) {
            setTimeout(() => {
                fetch(`${WIZARD_API}?action=get_categories&city=${encodeURIComponent(item.location)}`)
                .then(res => res.json())
                .then(catData => { wizardCache.categories[item.location] = catData; }).catch(e=>e);
            }, 50); // slight offset to not block main thread
        }

        const card = document.createElement('div');
        card.className = 'wizard-card';
        card.innerHTML = `<i class="fa fa-map-marker-alt"></i><span>${item.location}</span>`;
        card.onclick = () => selectCity(item.location);
        grid.appendChild(card);
    });
}

function loadCities() {
    // If we have cached data, use it immediately
    if (wizardCache.locations) {
        renderCities(wizardCache.locations);
        return;
    }

    const grid = document.getElementById('cityGrid');
    fetch(WIZARD_API + '?action=get_locations')
        .then(res => res.json())
        .then(data => {
            wizardCache.locations = data;
            renderCities(data);
        }).catch(err => { 
            grid.innerHTML = '<p class="text-center text-danger">Error loading cities.</p>';
        });
}

function selectCity(city) {
    selection.city = city;
    loadCategories();
    goToStep(2);
}

function renderCategories(data) {
    const grid = document.getElementById('categoryGrid');
    grid.innerHTML = '';
    if(!data || data.length === 0) {
        grid.innerHTML = '<p class="text-center">No categories found.</p>';
        return;
    }
    data.forEach(item => {
        // Background Pre-fetch Types
        const cacheKey = selection.city + '_' + item.id;
        if(!wizardCache.types[cacheKey]) {
            setTimeout(() => {
                fetch(`${WIZARD_API}?action=get_property_types&city=${encodeURIComponent(selection.city)}&category_id=${item.id}`)
                .then(res => res.json())
                .then(typeData => { wizardCache.types[cacheKey] = typeData; }).catch(e=>e);
            }, 50);
        }

        const card = document.createElement('div');
        card.className = 'wizard-card';
        const icon = item.name.toLowerCase().includes('comm') ? 'fa-building' : 'fa-home';
        card.innerHTML = `<i class="fa ${icon}"></i><span>${item.name}</span>`;
        card.onclick = () => selectCategory(item.id);
        grid.appendChild(card);
    });
}

function loadCategories() {
    // Check Cache first
    if (wizardCache.categories[selection.city]) {
        renderCategories(wizardCache.categories[selection.city]);
        return;
    }

    const grid = document.getElementById('categoryGrid');
    fetch(`${WIZARD_API}?action=get_categories&city=${encodeURIComponent(selection.city)}`)
        .then(res => res.json())
        .then(data => {
            wizardCache.categories[selection.city] = data;
            renderCategories(data);
        }).catch(err => { grid.innerHTML = '<p class="text-center text-danger">Error loading categories.</p>'; });
}

function selectCategory(catId) {
    selection.category_id = catId;
    loadTypes();
    goToStep(3);
}

function loadTypes() {
    const grid = document.getElementById('typeGrid');
    const cacheKey = selection.city + '_' + selection.category_id;
    
    // Check Cache first
    if (wizardCache.types[cacheKey]) {
        renderTypes(wizardCache.types[cacheKey]);
        return;
    }
    
    fetch(`${WIZARD_API}?action=get_property_types&city=${encodeURIComponent(selection.city)}&category_id=${selection.category_id}`)
        .then(res => res.json())
        .then(data => {
            wizardCache.types[cacheKey] = data;
            renderTypes(data);
        }).catch(err => { grid.innerHTML = '<p class="text-center text-danger">Error loading types.</p>'; });
}

function renderTypes(data) {
    const grid = document.getElementById('typeGrid');
    grid.innerHTML = '';
    if(!data || data.length === 0) {
        grid.innerHTML = '<p class="text-center">No types found.</p>';
        return;
    }
    data.forEach(item => {
        // Background Pre-fetch Developers
        const devCacheKey = selection.city + '_' + selection.category_id + '_' + item.id;
        if(!wizardCache.devs[devCacheKey]) {
            setTimeout(() => {
                fetch(`${WIZARD_API}?action=get_developers&city=${encodeURIComponent(selection.city)}&category_id=${selection.category_id}&subcategory_id=${item.id}`)
                .then(res => res.json())
                .then(devData => { wizardCache.devs[devCacheKey] = devData; }).catch(e=>e);
            }, 50);
        }

        const card = document.createElement('div');
        card.className = 'wizard-card';
        card.innerHTML = `<i class="fa fa-layer-group"></i><span>${item.property_type}</span>`;
        card.onclick = () => selectType(item.id);
        grid.appendChild(card);
    });
}

function selectType(subcatId) {
    selection.subcategory_id = subcatId;
    loadDevelopers();
    goToStep(4);
}

function loadDevelopers() {
    const grid = document.getElementById('devGrid');
    const devCacheKey = selection.city + '_' + selection.category_id + '_' + selection.subcategory_id;
    
    // Check Cache first
    if (wizardCache.devs[devCacheKey]) {
        renderDevelopers(wizardCache.devs[devCacheKey]);
        return;
    }
    
    fetch(`${WIZARD_API}?action=get_developers&city=${encodeURIComponent(selection.city)}&category_id=${selection.category_id}&subcategory_id=${selection.subcategory_id}`)
        .then(res => res.json())
        .then(data => {
            wizardCache.devs[devCacheKey] = data;
            renderDevelopers(data);
        }).catch(err => { grid.innerHTML = '<p class="text-center text-danger">Error loading developers.</p>'; });
}

function renderDevelopers(data) {
    const grid = document.getElementById('devGrid');
    grid.innerHTML = '';
    if(!data || data.length === 0) {
        grid.innerHTML = '<p class="text-center">No developers found.</p>';
        return;
    }
    data.forEach(item => {
        const card = document.createElement('div');
        card.className = 'wizard-card';
        card.innerHTML = `<i class="fa fa-user-tie"></i><span>${item.developer}</span>`;
        card.onclick = () => finishWizard(item.developer);
        grid.appendChild(card);
    });
}

function finishWizard(dev) {
    selection.developer = dev;
    const url = `<?= SITE_URL; ?>search_result.php?query=${encodeURIComponent(dev)}&location=${encodeURIComponent(selection.city)}&category_id=${selection.category_id}&subcategory_id=${selection.subcategory_id}`;
    window.location.href = url;
}
</script>
