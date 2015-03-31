function redux_spectrum_validate(F){var E=jQuery(F).val();return E}!function($){"use strict";$.redux=$.redux||{},$(document).ready(function(){$.redux.spectrum()}),$.redux.spectrum=function(){$(".redux-spectrum-init").spectrum({showAlpha:!0,showInput:!0,allowEmpty:!0,showPalette:!0,showButtons:!1,palette:[["#ffffff","#F5F5F5","#E0E0E0","#BDBDBD","#9E9E9E","#757575","#424242","#212121","#000000"],["#FFEBEE","#FCE4EC","#F3E5F5","#EDE7F6","#E8EAF6","#E3F2FD","#E1F5FE","#E0F7FA","#E0F2F1"],["#FFCDD2","#F8BBD0","#E1BEE7","#D1C4E9","#C5CAE9","#BBDEFB","#B3E5FC","#B2EBF2","#B2DFDB"],["#f44336","#e91e63","#9c27b0","#673ab7","#3f51b5","#2196f3","#03A9F4","#00BCD4","#009688"],["#B71C1C","#880E4F","#4A148C","#311B92","#1A237E","#0D47A1","#01579B","#006064","#004D40"],["#E8F5E9","#F1F8E9","#F9FBE7","#FFFDE7","#FFF8E1","#FFF3E0","#FBE9E7","#EFEBE9","#ECEFF1"],["#C8E6C9","#DCEDC8","#F0F4C3","#FFF9C4","#FFECB3","#FFE0B2","#FFCCBC","#D7CCC8","#CFD8DC"],["#4CAF50","#8BC34A","#CDDC39","#FFEB3B","#FFC107","#FF9800","#FF5722","#795548","#607D8B"],["#1B5E20","#33691E","#827717","#F57F17","#FF6F00","#E65100","#BF360C","#3E2723","#263238"]],localStorageKey:"redux.spectrum",showInitial:!0,preferredFormat:"hex"}),$(".redux-spectrum").on("change",function(){var F=$(this).spectrum("get"),E=F?F.toRgbString():"";jQuery(this).val(E)}),$(".redux-spectrum").on("focus",function(){$(this).data("oldcolor",$(this).val())}),$(".redux-spectrum").on("blur",function(){var F=$(this).spectrum("get"),E=F?F.toRgbString():"";jQuery(this).val(E)})}}(jQuery);