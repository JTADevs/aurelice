---
name: aurelice-design
description: "Brand identity and visual guidelines for the Aurelice Jewellery website. ALWAYS activate before creating or editing any page, Vue component, Blade view, or CSS in this project. Source materials (brand book PDF, reference screenshots, mockups) live in the git-ignored `references/` folder in the project root - open them whenever you need to check how something should look. Covers colours, fonts, logo usage, layout patterns, tone of copy."
metadata:
  author: aurelice
---

# Aurelice Jewellery – wytyczne wizualne

## Materiały źródłowe: folder `references/`

Folder `references/` w katalogu głównym projektu (ignorowany przez git) zawiera materiały
opisujące, jak ma wyglądać strona. **Zawsze zaglądaj tam przed pracą nad UI**, bo materiały
mogą przybywać (nowe makiety, zdjęcia produktów, teksty).

Obecna zawartość:

- `IDENTYFIKACJA WIZUALNA_AURELICE JEWELLERY.pdf` – księga znaku (6 stron: okładka, logotypy,
  2× strona referencyjna, kolorystyka, fonty).
- `image.png` … `image copy 5.png` – zrzuty stron PDF (czytaj obrazy narzędziem Read, gdy
  renderowanie PDF nie działa).

Przeglądaj obrazy przez Read (PNG działa zawsze). Jeśli pojawią się nowe pliki, przejrzyj je
i zaktualizuj ten skill.

## Kolorystyka (tylko dwa kolory bazowe)

| Token Tailwind    | HEX       | Użycie                                             |
|-------------------|-----------|----------------------------------------------------|
| `cream`           | `#F3F0ED` | tło strony, pasek informacyjny, tekst na bordo     |
| `burgundy`        | `#440512` | nagłówek, stopka, sekcje akcentowe, tekst na kremie |

Tokeny są zdefiniowane w `resources/css/app.css` (`@theme`). Odcienie pomocnicze twórz przez
przezroczystość (`text-burgundy/60`, `border-cream/20`), nie dodawaj nowych kolorów bez zgody.
Zdjęcia produktowe mają ciepłe, beżowe tony – placeholdery też trzymaj w tej gamie.

## Typografia

| Rola            | Font w brandbooku | Klasa Tailwind | Uwagi |
|-----------------|-------------------|----------------|-------|
| Logo, nagłówki  | **TAN Pearl**     | `font-display` | font komercyjny, nie ma go w repo. Tymczasowo Cormorant Garamond (Google Fonts). Gdy klient dostarczy pliki `.woff2`, wrzuć do `public/fonts/` i podmień `@font-face` w `app.css`. |
| Tekst, UI       | **Montserrat**    | `font-sans`    | wagi 300/400/500/600, Google Fonts. |

Zasady:
- Etykiety, przyciski, nawigacja: Montserrat, wielkie litery, duży tracking (`uppercase tracking-[0.2em]`), mały rozmiar (`text-xs`/`text-sm`).
- Nagłówki sekcji: `font-display`, waga normalna, bez uppercase (poza logo w mockupach, które jest w wersji "Aurelice" z wielkiej litery).
- Logo tekstowe: `Aurelice` (`font-display`) + pod spodem `JEWELLERY` (Montserrat, `tracking-[0.35em]`, ~10px). Sygnet: monogram litery „A".

## Układ strony (wg stron referencyjnych: umiar.com, effijewelry.com)

1. **Pasek informacyjny** na kremowym tle: `DARMOWA DOSTAWA DLA ZAMÓWIEŃ POWYŻEJ 300 ZŁ`.
2. **Nagłówek** na bordowym tle: menu (hamburger) po lewej, logo wyśrodkowane, ikony
   szukaj / konto / koszyk po prawej. Na desktopie pod logo pozioma nawigacja kategorii.
3. **Hero** pełnej szerokości ze zdjęciem, krótkim hasłem i przyciskiem outline `SPRAWDŹ` / `ODKRYJ KOLEKCJE`.
4. **Kolekcje** – siatka kafelków 3:4 (naszyjniki, kolczyki, bransoletki, pierścionki).
5. **Nowości** – nagłówek z eyebrow `NOWOŚCI`, opis, siatka produktów (nazwa + cena w zł).
6. **Sekcja o marce** na bordowym tle.
7. **Newsletter**, **stopka** (bordo).

Styl: minimalistyczny, dużo światła, duże marginesy, cienkie linie, brak zaokrągleń
(przyciski prostokątne), brak cieni. Mobile-first – mockupy są w układzie mobilnym.

## Ton komunikacji

Polski, zwięzły, elegancki. Opis marki: ręcznie wykonana biżuteria z naturalnych kamieni i pereł.
Przykłady haseł z referencji: „Rzeźba w ruchu", „Ręcznie wykonana biżuteria z naturalnych kamieni i pereł".

## Struktura frontendu i SEO

- Inertia.js v3 + SSR. Strony: `resources/js/pages/*.vue` (renderowane przez `Inertia::render` / `Route::inertia`),
  komponenty sekcji: `resources/js/components/*.vue`. Root view: `resources/views/app.blade.php`.
- Każda strona ustawia `<Head>` (title + meta description). Pełne zasady SEO są w `CLAUDE.md`,
  sekcja „Zasady projektu Aurelice" – przestrzegaj ich przy każdej nowej stronie.
- Placeholder zdjęć: komponent `ImagePlaceholder.vue` – podmieniaj na `<img>` gdy dostaniesz zdjęcia.
- Dark mode: **nie** – strona ma jeden, jasny wygląd.
