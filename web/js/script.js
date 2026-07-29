// 	var slider = [];
// 	var output = [];
// 	var result = [];
// 	var j
//     for (var i = 0; i <= 30; ++i) {
//     	j = i;
//         slider[j] = document.getElementById(`myRange${j}`);
//         output[j] = document.getElementById(`demo${j}`);
//         if(slider[j].value == 1){
// 			result[j] = 'Не знаю'
// 		}
// 		console.log(i);
// 		output[j].innerHTML = result[j];
// 		slider[j].oninput = function() {
// 		console.log(this.name);
// 		if(this.value == 1){
// 			result[j]='Не знаю'
// 		}
// 		if(this.value == 2){
// 			result[j]='Никогда'
// 		}
// 		if(this.value == 3){
// 			result[j]='Редко'
// 		}
// 		if(this.value == 4){
// 			result[j]='Часто'
// 		}
// 		if(this.value == 5){
// 			result[j]='Всегда'
// 		}
//   		output[this.name].innerHTML = result[j];
// 	}
// }
