export const API_URL = 'events'

export const MODEL_DEFAULTS = {

}

export const TYPE = [
  {id: 'like', title: 'лайк'},
  {id: 'dislike', title: 'дизлайк'},
  {id: 'ban', title: 'бан'},
  {id: 'removed-from-you-want-to-meet-list', title: 'удалить из списка «Вы хотите встретиться»'},
  {id: 'removed-from-want-to-meet-you-list', title: 'удалить из списка «С вами хотят встретиться»'},
  {id: 'removed-from-dates-list', title: 'удалить из списка «Свидания»'},
]

export const FILTERS = [
  {field: 'type', type: 'multiple', label: 'тип', options: TYPE}
]