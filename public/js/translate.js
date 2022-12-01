import en from './lang/en.js';

export function T(str) {
  return en[str] ? en[str] : console.info(`there are no translation for "${str}"`) || str;
};