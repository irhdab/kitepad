(() => {
  const DICT = {
    en: {
      "nav.create": "Create New Paste",
      "nav.view": "View Posts",
      "nav.back": "Back",
      "nav.contact": "Contact",
      "nav.privacy": "Privacy Policy",
      "common.github": "GitHub",
      "lang.en": "EN",
      "lang.ko": "KO",
      "tagline": "Zero-knowledge pastebin. Configure expiry, view limit and end-to-end encryption in one place.",
      "index.title": "KitePad",
      "index.titleLabel": "Title (Optional)",
      "index.titlePlaceholder": "Enter title (e.g. My Notes)",
      "index.expiration": "Paste Expiration:",
      "index.exposure": "Paste Exposure:",
      "index.viewLimit": "View Limit (Optional)",
      "index.password": "Password (optional)",
      "index.editor": "Editor",
      "index.preview": "Preview",
      "index.publish": "Publish",
      "index.update": "Update Post",
      "index.edit": "Edit Paste",
      "index.ready": "Your paste is ready!",
      "index.copy": "Copy",
      "index.protip": "Pro tip: You can access the raw text by adding",
      "index.secretWarning": "Looks like this might contain credentials. Make sure you trust the recipient.",
      "index.e2ee": "End-to-End Encryption (E2EE)",
      "index.e2eeDesc": "Password will be required to decrypt. Content is never sent raw to server.",
      "view.single": "Paste Detail",
      "view.list": "Public Pastes",
      "view.copyLink": "Copy Link",
      "view.qr": "QR Code",
      "view.zen": "Zen Mode",
      "view.exitZen": "Exit Zen Mode",
      "view.copyContent": "Copy Content",
      "view.copyEmbed": "Copy Embed Code",
      "view.copyRaw": "Copy Raw",
      "view.view": "View",
      "view.clone": "Clone",
      "view.edit": "Edit",
      "view.decrypt": "Decrypt Content",
      "view.decryptPlaceholder": "Password to decrypt",
      "view.protected": "This paste is password protected.",
      "view.unlock": "Unlock",
      "view.scanShare": "Scan to share",
      "view.noContent": "No content available.",
      "view.previous": "Previous",
      "view.next": "Next",
      "footer.rights": "All rights reserved.",
      "contact.title": "Contact",
      "contact.subtitle": "If you have any questions, please contact us at the email address below.",
      "contact.support": "Email Support",
      "contact.response": "We typically try to respond within 24 hours. We welcome any feedback, including service suggestions or bug reports.",
      "privacy.title": "Privacy Policy"
      ,"alert.sessionLoading": "Security session is still loading. Please wait a second and try again."
      ,"alert.emptyContent": "Please write something before publishing."
      ,"alert.tooLarge": "The content is too large. The maximum size is 1MB"
      ,"alert.e2eePassword": "Encryption requires a password."
      ,"alert.encryptFailed": "Encryption failed."
      ,"alert.decryptFailed": "Decryption failed. Incorrect password?"
      ,"alert.linkCopied": "Link copied to clipboard!"
      ,"alert.contentCopied": "Content copied to clipboard!"
      ,"alert.copied": "Copied to clipboard!"
    },
    ko: {
      "nav.create": "새 글 작성",
      "nav.view": "글 목록",
      "nav.back": "뒤로",
      "nav.contact": "문의",
      "nav.privacy": "개인정보처리방침",
      "common.github": "GitHub",
      "lang.en": "EN",
      "lang.ko": "KO",
      "tagline": "제로-노하우지 페이스트빈. 만료, 조회 제한, 종단간 암호화를 한 곳에서 설정하세요.",
      "index.title": "KitePad",
      "index.titleLabel": "제목 (선택)",
      "index.titlePlaceholder": "제목 입력 (예: 내 노트)",
      "index.expiration": "게시물 만료:",
      "index.exposure": "공개 범위:",
      "index.viewLimit": "조회 제한 (선택)",
      "index.password": "비밀번호 (선택)",
      "index.editor": "에디터",
      "index.preview": "미리보기",
      "index.publish": "발행",
      "index.update": "게시물 수정",
      "index.edit": "게시물 편집",
      "index.ready": "게시물이 준비되었습니다!",
      "index.copy": "복사",
      "index.protip": "팁: URL에 다음을 추가하면 원문 텍스트를 볼 수 있습니다",
      "index.secretWarning": "자격 증명으로 보이는 내용이 포함되어 있습니다. 신뢰할 수 있는 수신자에게만 공유하세요.",
      "index.e2ee": "종단간 암호화 (E2EE)",
      "index.e2eeDesc": "복호화를 위해 비밀번호가 필요합니다. 콘텐츠 원문은 서버로 전송되지 않습니다.",
      "view.single": "게시물 상세",
      "view.list": "공개 게시물",
      "view.copyLink": "링크 복사",
      "view.qr": "QR 코드",
      "view.zen": "집중 모드",
      "view.exitZen": "집중 모드 종료",
      "view.copyContent": "내용 복사",
      "view.copyEmbed": "임베드 코드 복사",
      "view.copyRaw": "원문 복사",
      "view.view": "보기",
      "view.clone": "복제",
      "view.edit": "수정",
      "view.decrypt": "내용 복호화",
      "view.decryptPlaceholder": "복호화 비밀번호",
      "view.protected": "이 게시물은 비밀번호로 보호됩니다.",
      "view.unlock": "잠금 해제",
      "view.scanShare": "스캔하여 공유",
      "view.noContent": "표시할 콘텐츠가 없습니다.",
      "view.previous": "이전",
      "view.next": "다음",
      "footer.rights": "모든 권리 보유.",
      "contact.title": "문의",
      "contact.subtitle": "문의 사항이 있다면 아래 이메일 주소로 연락해 주세요.",
      "contact.support": "이메일 지원",
      "contact.response": "보통 24시간 이내 답변드립니다. 서비스 제안이나 버그 제보를 포함한 모든 피드백을 환영합니다.",
      "privacy.title": "개인정보처리방침",
      "alert.sessionLoading": "보안 세션을 준비 중입니다. 잠시 후 다시 시도해 주세요.",
      "alert.emptyContent": "발행하기 전에 내용을 작성해 주세요.",
      "alert.tooLarge": "콘텐츠가 너무 큽니다. 최대 크기는 1MB입니다.",
      "alert.e2eePassword": "암호화를 사용하려면 비밀번호가 필요합니다.",
      "alert.encryptFailed": "암호화에 실패했습니다.",
      "alert.decryptFailed": "복호화에 실패했습니다. 비밀번호를 확인해 주세요.",
      "alert.linkCopied": "링크를 클립보드에 복사했습니다!",
      "alert.contentCopied": "내용을 클립보드에 복사했습니다!",
      "alert.copied": "클립보드에 복사했습니다!"
    }
  };

  function normalizeLang(v) {
    return v === "ko" ? "ko" : "en";
  }

  function readLangFromUrl() {
    const params = new URLSearchParams(window.location.search);
    return normalizeLang(params.get("lang"));
  }

  function readLang() {
    const fromUrl = new URLSearchParams(window.location.search).get("lang");
    if (fromUrl) return normalizeLang(fromUrl);
    const fromStorage = localStorage.getItem("kitepad_lang");
    if (fromStorage) return normalizeLang(fromStorage);
    return normalizeLang((navigator.language || "en").slice(0, 2));
  }

  function setLang(lang, updateUrl = true) {
    const normalized = normalizeLang(lang);
    localStorage.setItem("kitepad_lang", normalized);
    document.documentElement.lang = normalized;
    if (updateUrl) {
      const url = new URL(window.location.href);
      url.searchParams.set("lang", normalized);
      window.history.replaceState({}, "", url.toString());
    }
    apply(normalized);
  }

  function t(key, fallback = "") {
    const lang = readLang();
    return DICT[lang]?.[key] || DICT.en[key] || fallback || key;
  }

  function apply(lang) {
    const target = normalizeLang(lang || readLang());
    document.querySelectorAll("[data-i18n]").forEach((el) => {
      const key = el.getAttribute("data-i18n");
      const translated = DICT[target]?.[key] || DICT.en[key];
      if (translated) el.textContent = translated;
    });
    document.querySelectorAll("[data-i18n-placeholder]").forEach((el) => {
      const key = el.getAttribute("data-i18n-placeholder");
      const translated = DICT[target]?.[key] || DICT.en[key];
      if (translated) el.setAttribute("placeholder", translated);
    });
    document.querySelectorAll("[data-i18n-title]").forEach((el) => {
      const key = el.getAttribute("data-i18n-title");
      const translated = DICT[target]?.[key] || DICT.en[key];
      if (translated) el.setAttribute("title", translated);
    });
    document.querySelectorAll("[data-lang-switch]").forEach((el) => {
      const selected = el.getAttribute("data-lang-switch");
      const link = new URL(window.location.href);
      link.searchParams.set("lang", normalizeLang(selected));
      el.setAttribute("href", link.pathname + link.search + link.hash);
      el.classList.toggle("active-lang", normalizeLang(selected) === target);
    });
  }

  function init() {
    const fromUrl = new URLSearchParams(window.location.search).get("lang");
    const lang = fromUrl ? normalizeLang(fromUrl) : readLang();
    setLang(lang, !!fromUrl);
  }

  window.KiteI18n = {
    init,
    setLang,
    t,
    getLang: readLang
  };
})();
