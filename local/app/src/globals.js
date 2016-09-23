export const BASE_URL = window.base_url || "http://localhost";
export const BASE_PATH = BASE_URL.match(/^(http:|https:)?\/\/([^\/]+)(.*)$/)[3];
export const API_VERSION = "v1";
export const OBJECT_RESOURCE_PATH = "user_objects";
export const MOBILE_WIDTH_PX = 768;

export const BASE_API_URL = BASE_URL + "/api/" + API_VERSION;
