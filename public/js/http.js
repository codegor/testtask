import { T } from './translate.js';

const CONTENT_TYPE_JSON = 'application/json';
const defaultOptions = {
  headers: {
    'Content-Type': CONTENT_TYPE_JSON,
  },
};

export class FetchException {
  constructor(code, text, description) {
    this.code = code;
    this.text = text;
    this.description = description;
  }
}

async function send(url, options) {
  const allOptions = options ? { ...defaultOptions, ...options } : defaultOptions;

  const response = await fetch(url, allOptions);

  if (201 === response.status)
    return {};

  const isJsonResponse = response.headers.get('Content-Type').includes(CONTENT_TYPE_JSON);

  if (!isJsonResponse)
    throw new FetchException(response.status, T('Error'), T('Wrong response format.'));

  const body = await response.json();
  if (!response.ok) {
    const details = body.error || body;
    throw new FetchException(response.status, T('Error'), T(details));
  }
  return body;
}

export class HttpClient {
  constructor(baseUrl) {
    this.baseUrl = baseUrl;
  }

  getJson(relativeUrl, options) {
    return send(`${this.baseUrl}${relativeUrl}`, options);
  }

  patchJson(relativeUrl, body) {
    const options = {
      method: 'PATCH',
      body: JSON.stringify(body),
    };
    return send(`${this.baseUrl}${relativeUrl}`, options);
  }

  postJson(relativeUrl, body) {
    const options = {
      method: 'POST',
      body: JSON.stringify(body),
    };
    return send(`${this.baseUrl}${relativeUrl}`, options);
  }

  putJson(relativeUrl, body) {
    const options = {
      method: 'PUT',
      body: JSON.stringify(body),
    };
    return send(`${this.baseUrl}${relativeUrl}`, options);
  }
};