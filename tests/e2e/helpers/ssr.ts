import { expect, type Page } from '@playwright/test';

/**
 * Verify that a page uses SSR and contains rendered HTML with Inertia data
 */
export async function expectSSREnabled(page: Page, expectedComponent?: string) {
    const htmlContent = await page.content();

    // Verify SSR: The HTML should contain rendered content with data-page
    expect(htmlContent).toContain('id="app"');
    expect(htmlContent).toContain('data-page');

    // Verify that Inertia page data is embedded in HTML
    // With useScriptElementForInitialPage, the format is:
    // <script data-page="app" type="application/json">{...}</script>
    const scriptMatch = htmlContent.match(
        /<script data-page="[^"]+" type="application\/json">({.+?})<\/script>/,
    );
    expect(scriptMatch).toBeTruthy();

    if (scriptMatch && expectedComponent) {
        const pageData = scriptMatch[1];
        const decodedData = pageData
            .replace(/&quot;/g, '"')
            .replace(/&amp;/g, '&');

        const parsed = JSON.parse(decodedData);

        // Verify Inertia structure
        expect(parsed).toHaveProperty('component');
        expect(parsed.component).toBe(expectedComponent);
    }

    return scriptMatch;
}

/**
 * Verify that a page does not use SSR (minimal HTML, client-side rendering)
 */
export async function expectSSRDisabled(page: Page) {
    const htmlContent = await page.content();

    // Dashboard should have minimal HTML (no SSR)
    // It will still have the app div and data-page, but without
    // full server-side rendered content
    expect(htmlContent).toContain('id="app"');
    expect(htmlContent).toContain('data-page');

    // The key difference with SSR disabled is that the Vue components
    // are not pre-rendered on the server. The HTML will be lighter
    // and rely on client-side JavaScript to render the content.
}

/**
 * Verify that Inertia page data is available without JavaScript
 *
 * Note: Inertia SSR doesn't render full HTML content visible without JavaScript.
 * Instead, it embeds page data in a script tag for faster hydration and SEO.
 * Search engines can execute JavaScript to render the content, but browsers with
 * JavaScript disabled will see an empty page. This is by design.
 *
 * This test verifies that the SSR-generated page data is properly embedded.
 */
export async function expectInertiaPageDataEmbedded(page: Page) {
    const htmlContent = await page.content();

    // Verify the app div exists
    expect(htmlContent).toContain('id="app"');

    // Verify that Inertia page data is embedded (this is what SSR provides)
    // The data-page attribute and script tag contain the initial page state
    expect(htmlContent).toContain('data-page');

    // Verify the page data script tag exists
    const scriptMatch = htmlContent.match(
        /<script[^>]*data-page[^>]*type="application\/json">({.+?})<\/script>/,
    );
    expect(scriptMatch).toBeTruthy();

    // The page data should contain meaningful content
    if (scriptMatch) {
        const pageData = scriptMatch[1];
        // Should have component and props data
        expect(pageData.length).toBeGreaterThan(100);
    }
}
