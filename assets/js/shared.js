const cities = ['surabaya', 'jakarta', 'semarang'];

export function getCities() {
   return cities.map(item => ({
      value: item,
      text: item.charAt(0).toUpperCase() + item.slice(1)
   }));
}