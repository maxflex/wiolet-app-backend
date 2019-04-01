const API_URL = 'settings'

export default class Settings {
  static get(key, json = false) {
    json = Number(json)
    return axios.get(apiUrl(API_URL + queryString({ key, json })))
  }

  static async set(key, value, json = false) {
    json = Number(json)
    await axios.post(apiUrl(API_URL), { key, json, value })
  }
}