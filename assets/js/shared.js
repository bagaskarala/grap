// static cities data
// const cities = ['surabaya', 'jakarta', 'semarang'];
// export function getCities() {
//    return cities.map(item => ({
//       value: item,
//       text: item.charAt(0).toUpperCase() + item.slice(1)
//    }));
// }

// example input 2, output 2nd
export function parseWinner(num) {
   if (num == 1) {
      return '1st';
   } else if (num == 2) {
      return '2nd';
   } else if (num == 3) {
      return '3rd';
   } else {
      return null;
   }
}