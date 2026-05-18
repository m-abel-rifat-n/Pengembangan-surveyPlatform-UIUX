import { defineConfig } from "cypress";

export default defineConfig({
    e2e: {
        baseUrl: "http://127.0.0.1:8000",
        defaultCommandTimeout: 10000,
        pageLoadTimeout: 30000,
        setupNodeEvents(on, config) {},
    },
});
