// ============================================================
// UIX-Probe — Full E2E: Raw NASA-TLX & VisAWI-S
//
// Flow:
//   Phase 1 — Admin creates one NASA-TLX survey and one VisAWI-S survey
//   Phase 2 — Respondent finds each on /surveys and submits valid answers
//   Phase 3 — Admin opens each results page and verifies KPI cards / charts
//
// Before running:
//   1. Replace ADMIN_EMAIL / ADMIN_PASSWORD with a real admin account.
//   2. Replace USER_EMAIL / USER_PASSWORD with a DIFFERENT real account
//      whose profile has first_name, surname, birth_date, gender,
//      profession, and educational_background already saved
//      (FormController validates all these from auth.user).
//   3. Both accounts must already exist in your local database.
//   4. Run: php artisan serve --port=8000  AND  npm run dev
// ============================================================

const ADMIN_EMAIL    = 'admin@123';
const ADMIN_PASSWORD = '123';
const USER_EMAIL     = 'basic@123';
const USER_PASSWORD  = '123';

// Unique suffix per run so repeated runs never collide on title/slug
const RUN_ID       = Date.now();
const NASA_TITLE   = `Cypress NASA-TLX ${RUN_ID}`;
const VISAWI_TITLE = `Cypress VisAWI-S ${RUN_ID}`;

// ── Shared login helper using cy.session() ──────────────────
// cy.session() caches browser cookies after the first login;
// subsequent calls with the same key just restore the cache.
function sessionAs(role, email, password) {
    cy.session(
        role,
        () => {
            cy.visit('/login');
            cy.get('input[placeholder="Enter your email"]').type(email);
            cy.get('input[placeholder="Enter your password"]').type(password);
            cy.get('button[type="submit"]').click();
            cy.url({ timeout: 15000 }).should('include', '/account/dashboard');
        },
        {
            validate() {
                // Confirm the session is still alive before reusing it
                cy.request({ url: '/account/dashboard', failOnStatusCode: false })
                  .its('status')
                  .should('eq', 200);
            },
        }
    );
}

// ── Helper: fill the survey-create form ────────────────────
function fillCreateSurveyForm(title, theme, methodCheckboxId) {
    cy.visit('/account/surveys/create');

    // Title — InputField placeholder is derived from the label
    cy.get('input[placeholder="Enter title, e.g., E-Learning Platform SmartLearn"]')
        .clear()
        .type(title);

    // Theme
    cy.get('input[placeholder="Enter theme, e.g., E-Learning Platform"]')
        .clear()
        .type(theme);

    // Description — Quill editor renders a contenteditable div with class .ql-editor
    cy.get('.ql-editor').click().type('Cypress automated test survey.');

    // URL Website — one of url_website / embed_design / embed_prototype is required
    cy.get('input[placeholder*="https://example.com"]')
        .clear()
        .type('https://example.com');

    // Categories — SelectCheckbox id="categories", checkbox id = check-categories-{value}
    // Pick whichever category appears first in the list
    cy.get('[id^="check-categories-"]').first().check({ force: true });

    // Method — SelectCheckbox id="methods", checkbox id = check-methods-{method_id}
    // NASA-TLX = method_id 5, VisAWI-S = method_id 6
    cy.get(methodCheckboxId).check({ force: true });

    // General access — RadioSelect id="survey_visibility"
    // Option {id:1, value:"Public"} → id = radio-survey_visibility-1
    cy.get('#radio-survey_visibility-1').check({ force: true });
}

// ============================================================
// PHASE 1 — Admin creates both surveys
// ============================================================
describe('Phase 1 — Admin creates Raw NASA-TLX and VisAWI-S surveys', () => {
    beforeEach(() => {
        sessionAs('admin', ADMIN_EMAIL, ADMIN_PASSWORD);
    });

    // ── Test 1a ─────────────────────────────────────────────
    it('creates the Raw NASA-TLX survey and lands on /account/surveys', () => {
        fillCreateSurveyForm(NASA_TITLE, 'E-Learning Platform', '#check-methods-5');

        // Save — ButtonCRUD type="submit" label="Save"
        cy.get('button[type="submit"]').click();

        // SurveyController::store() redirects to account.surveys.index (/account/surveys)
        // then Inertia fires onSuccess → Swal "Success!" (timer 1500ms, no confirm button)
        cy.url({ timeout: 15000 }).should('include', '/account/surveys');

        // SweetAlert appears on the redirected page; wait for auto-close
        cy.get('.swal2-popup', { timeout: 8000 }).should('be.visible');
        cy.get('.swal2-title').should('contain', 'Success!');
        cy.wait(2000); // 1500ms timer + buffer
    });

    // ── Test 1b ─────────────────────────────────────────────
    it('creates the VisAWI-S survey and lands on /account/surveys', () => {
        fillCreateSurveyForm(VISAWI_TITLE, 'E-Commerce Platform', '#check-methods-6');

        cy.get('button[type="submit"]').click();

        cy.url({ timeout: 15000 }).should('include', '/account/surveys');

        cy.get('.swal2-popup', { timeout: 8000 }).should('be.visible');
        cy.get('.swal2-title').should('contain', 'Success!');
        cy.wait(2000);
    });
});

// ============================================================
// PHASE 2 — Respondent finds and fills both surveys
// ============================================================
describe('Phase 2 — Respondent fills both surveys on /surveys', () => {
    beforeEach(() => {
        sessionAs('respondent', USER_EMAIL, USER_PASSWORD);
    });

    // ── Test 2a — NASA-TLX ──────────────────────────────────
    it('finds the NASA-TLX survey, fills all 6 sliders, and submits', () => {
        // Search for the survey by title on the public surveys page
        cy.visit('/surveys');

        // Search component: input[placeholder="type keywords and press enter..."]
        // Submits via Inertia.get(`surveys?q=${search}`)
        cy.get('input[placeholder="type keywords and press enter..."]').type(NASA_TITLE);
        cy.get('input[placeholder="type keywords and press enter..."]').type('{enter}');

        // CardItem renders a Link (→ <a>) wrapping the card; the href is /form/{id}/{slug}
        // cy.contains('a', text) finds the <a> whose subtree contains the title text
        cy.contains('a', NASA_TITLE, { timeout: 15000 }).click();

        // Now on /form/{survey_id}/{slug}
        cy.url({ timeout: 10000 }).should('include', '/form/');

        // The NASA-TLX section is wrapped in AccordionLayout (defaultOpen = false).
        // The header div has class .accordion-header and its onClick toggles the panel.
        // Title in DOM: "Raw NASA-TLX (Mental Workload Assessment)"
        cy.contains('.accordion-header', 'Raw NASA-TLX').click();

        // NasaTlxForm renders inside the now-open accordion
        cy.contains('NASA-TLX Assessment', { timeout: 5000 }).should('be.visible');

        // 6 range sliders rendered by NasaTlxForm in this order:
        //   mental_demand, physical_demand, temporal_demand, performance, effort, frustration
        // All use min=0, max=100, step=10
        // performance and frustration are inverted internally (100 - value stored),
        // but the slider itself still accepts 0-100.
        const sliderValues = [60, 40, 50, 30, 70, 20];

        cy.get('input[type="range"]').each(($slider, index) => {
            cy.wrap($slider)
                .invoke('val', sliderValues[index])
                .trigger('input',  { force: true }) // React listens to input
                .trigger('change', { force: true }); // NasaTlxForm.onChange
        });

        // Verify badge displays show updated values (NasaTlxForm badge: class="badge bg-primary")
        // getDisplayValue returns stored value (already inverted for performance/frustration)
        cy.get('.badge.bg-primary').should('have.length.gte', 6);

        // Submit — button text is "Submit Survey Response" (class="btn py-3")
        cy.get('button[type="submit"]').contains('Submit Survey Response').click();

        // Form.jsx onSuccess: Swal.fire({ title: "Thank You!", timer: 3000 })
        // then .then(() => Inertia.visit("/"))
        cy.get('.swal2-title', { timeout: 15000 }).should('contain', 'Thank You!');
        cy.get('.swal2-content, .swal2-html-container')
            .should('contain', 'Survey data submitted successfully!');

        // After the 3000ms timer, Inertia navigates to "/"
        cy.url({ timeout: 8000 }).should('not.include', '/form/');
    });

    // ── Test 2b — VisAWI-S ──────────────────────────────────
    it('finds the VisAWI-S survey, answers all 4 radio dimensions, and submits', () => {
        cy.visit('/surveys');

        cy.get('input[placeholder="type keywords and press enter..."]').type(VISAWI_TITLE);
        cy.get('input[placeholder="type keywords and press enter..."]').type('{enter}');

        cy.contains('a', VISAWI_TITLE, { timeout: 15000 }).click();

        cy.url({ timeout: 10000 }).should('include', '/form/');

        // VisAWI-S accordion: title "VisAWI-S (Visual Aesthetics Assessment)"
        cy.contains('.accordion-header', 'VisAWI-S').click();

        cy.contains('VisAWI-S Assessment', { timeout: 5000 }).should('be.visible');

        // VisawiSForm.jsx radio inputs — name attribute = dimension key, value = 1-7
        // 4 dimensions with 7 options each, all marked required (native browser validation)
        cy.get('input[type="radio"][name="simplicity"][value="5"]')
            .scrollIntoView({ block: 'center' }).click({ force: true });
        cy.get('input[type="radio"][name="diversity"][value="4"]')
            .scrollIntoView({ block: 'center' }).click({ force: true });
        cy.get('input[type="radio"][name="colorfulness"][value="6"]')
            .scrollIntoView({ block: 'center' }).click({ force: true });
        cy.get('input[type="radio"][name="craftsmanship"][value="5"]')
            .scrollIntoView({ block: 'center' }).click({ force: true });

        // Confirm all 4 are selected before submitting
        cy.get('input[type="radio"][name="simplicity"]:checked').should(
            'have.value', '5'
        );
        cy.get('input[type="radio"]:checked').should('have.length', 4);

        cy.get('button[type="submit"]').contains('Submit Survey Response').click();

        cy.get('.swal2-title', { timeout: 15000 }).should('contain', 'Thank You!');
        cy.get('.swal2-content, .swal2-html-container')
            .should('contain', 'Survey data submitted successfully!');

        cy.url({ timeout: 8000 }).should('not.include', '/form/');
    });
});

// ============================================================
// PHASE 3 — Admin reviews results for both surveys
// ============================================================
describe('Phase 3 — Admin verifies results pages', () => {
    beforeEach(() => {
        sessionAs('admin', ADMIN_EMAIL, ADMIN_PASSWORD);
    });

    // ── Test 3a — NASA-TLX results ──────────────────────────
    it('verifies NASA-TLX results: 1 respondent, KPI cards, and charts present', () => {
        // NasaTlxController::index() redirects to the first (lowest id) NASA-TLX survey.
        cy.visit('/account/nasa-tlx');
        cy.url({ timeout: 10000 }).should('match', /\/account\/nasa-tlx\/\d+/);

        // Use the "Pilih Survey" Bootstrap dropdown to navigate to OUR survey.
        // Dropdown toggle button id="dropdownMenuButton" (from Index.jsx)
        cy.get('#dropdownMenuButton').click();
        cy.get('.dropdown-item').contains(NASA_TITLE, { timeout: 8000 }).click();

        // Inertia navigates to /account/nasa-tlx/{id}
        cy.url({ timeout: 10000 }).should('match', /\/account\/nasa-tlx\/\d+/);

        // Page title set via <Head><title>NASA-TLX Result - UIX-Probe</title></Head>
        cy.title().should('eq', 'NASA-TLX Result - UIX-Probe');

        // Header shows: "Hasil : {survey title}"
        cy.contains('Hasil').should('be.visible');
        cy.contains(NASA_TITLE).should('be.visible');

        // ── InfoCard: Jumlah Responden ──────────────────────
        // InfoCard labels live inside an overflow:hidden ancestor so Cypress
        // considers them clipped. Use .should('exist') instead of 'be.visible'.
        cy.contains('Jumlah Responden').should('exist');

        // respondentCount = 1 after Phase 2 submission
        cy.contains('.h4', '1').should('exist');

        // ── InfoCard: Skor NASA-TLX Total ──────────────────
        cy.contains('Skor NASA-TLX Total').should('exist');
        cy.contains('dari 100').should('exist');

        // ── InfoCard: Level Beban Kerja ─────────────────────
        cy.contains('Level Beban Kerja').should('exist');
        cy.contains(/Tinggi|Sedang|Rendah/).should('exist');

        // ── Kesimpulan / dimension averages ────────────────
        cy.contains('Kesimpulan').should('exist');
        cy.contains('Mental Demand').should('exist');
        cy.contains('Physical Demand').should('exist');

        // ── Demographic charts ──────────────────────────────
        // PieChart renders <canvas> elements; demographics accordion is shown
        // if respondentCount > 0
        cy.contains('Demografi', { timeout: 5000 }).should('exist');
        cy.get('canvas').should('have.length.gte', 1);
    });

    // ── Test 3b — VisAWI-S results ──────────────────────────
    it('verifies VisAWI-S results: 1 respondent, KPI cards, and charts present', () => {
        cy.visit('/account/visawi-s');
        cy.url({ timeout: 10000 }).should('match', /\/account\/visawi-s\/\d+/);

        cy.get('#dropdownMenuButton').click();
        cy.get('.dropdown-item').contains(VISAWI_TITLE, { timeout: 8000 }).click();

        cy.url({ timeout: 10000 }).should('match', /\/account\/visawi-s\/\d+/);

        // Page title from <Head>
        cy.title().should('eq', 'VisAWI-S Result - UIX-Probe');

        // Header
        cy.contains('Hasil').should('be.visible');
        cy.contains(VISAWI_TITLE).should('be.visible');

        // ── InfoCard: Jumlah Responden ──────────────────────
        // Same overflow:hidden clipping as NASA-TLX — use .should('exist').
        cy.contains('Jumlah Responden').should('exist');
        cy.contains('.h4', '1').should('exist');

        // ── InfoCard: Skor VisAWI-S Total ──────────────────
        cy.contains('Skor VisAWI-S Total').should('exist');
        cy.contains('dari 7').should('exist');

        // ── InfoCard: Estetika Visual ───────────────────────
        cy.contains('Estetika Visual').should('exist');
        cy.contains(/Baik|Cukup|Perlu Perbaikan/).should('exist');

        // ── Kesimpulan / dimension averages ────────────────
        cy.contains('Kesimpulan').should('exist');
        cy.contains('Simplicity').should('exist');
        cy.contains('Diversity').should('exist');
        cy.contains('Colorfulness').should('exist');
        cy.contains('Craftsmanship').should('exist');

        // ── Demographic charts ──────────────────────────────
        cy.contains('Demografi', { timeout: 5000 }).should('exist');
        cy.get('canvas').should('have.length.gte', 1);
    });
});
