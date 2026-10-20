AMP HEATING & COOLING – FULL CUSTOMER WEBSITE + AI CHAT

PAGES
Home, Heating, Cooling, About, Service Areas, Request Service, Contact.
AI floating chat widget included on every page. Mobile-responsive layout.

OPEN IN VISUAL STUDIO CODE (Windows)
1. Right-click ZIP > Extract All.
2. VS Code > File > Open Folder > choose extracted amp-website folder.
3. Preview the design by opening index.html or using Live Server.
IMPORTANT: Live Server does NOT execute PHP. The chat and request forms need a PHP server to function.

RUN LOCALLY WITH PHP
1. Install PHP 8.1+ (with the curl extension).
2. In the VS Code terminal inside the website folder, run: php -S localhost:8000
3. Visit http://localhost:8000 in your browser.
Without an OPENAI_API_KEY, chat replies using built-in demo FAQs. No third-party AI is called in demo mode.

ENABLE REAL AI
1. Create a server-side OpenAI API key and configure billing with the API provider. API calls may have costs.
2. Set OPENAI_API_KEY as a SERVER ENVIRONMENT VARIABLE (never paste it into JS/HTML or commit it to git).
   PowerShell example for LOCAL development only: $env:OPENAI_API_KEY="your-key"
   Then run: php -S localhost:8000
3. OPENAI_MODEL defaults to gpt-4.1-mini; set a model name if you need another supported model.
4. The chat posts to ai-chat.php on your own PHP server. That server sends user chat messages to OpenAI only when the key is set.
5. Configure host firewall/rate limits and a privacy policy explaining AI processing prior to going live.

ENABLE SERVICE REQUEST FORMS
Set AMP_REQUEST_EMAIL to the destination inbox, and AMP_SENDER_EMAIL to a verified sender address.
The website uses PHP mail() to deliver forms and requires a properly configured outbound email server.
Preview alone is not enough; test end-to-end after deployment. Requests do NOT automatically book appointments.

BEFORE PRODUCTION LAUNCH
Use HTTPS and verified domain; set spam protection/rate limiting, privacy policy and retention settings; make sure PHP mail() is supported or replace with authenticated SMTP; validate business hours, service area, contact details, photos and claims; enable reliable logging and alerting; test the request form and live chat on desktop/mobile.
The live AI may be wrong and is not a certified HVAC technician. Do not share sensitive payment data in the chat.

SERVICE AREA: Chicago, surrounding suburbs, and greater Chicagoland. Ask callers to confirm specific address coverage.
