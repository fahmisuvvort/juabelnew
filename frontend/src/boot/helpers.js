import Vue from 'vue'

Vue.mixin({
  methods: {
    moneyIDR(numb) {
      return 'Rp '+ numb.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    },    
    $money(numb) {
      return numb.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    },  
    generateSku(numb = 11) {
      let result = ''
      var randomChars = 'ABCDEFGHIJKL9MNOPQRST8UVWXYZ01T2343567890';

      for(let j = 0; j < 2; j++) {

        
        for ( var i = 0; i < numb; i++ ) {
          result += randomChars.charAt(Math.floor(Math.random() * randomChars.length));
        }

        result += '-'

      }

      return result;
    } , 
    getRandomString(numb) {
      let result = ''
      var randomChars = 'ABCDEFGHIJKL9MNOPQRST8UVWXYZ01T2343567890abcdefghijklmnopqrstuvwxyz';

      for ( var i = 0; i < numb; i++ ) {
          result += randomChars.charAt(Math.floor(Math.random() * randomChars.length));
      }
      return result;
    },
    makeSessionId() {
      let result = this.getRandomString(39)
      
      this.$store.commit('SET_SESSION_ID', result);
    }
  },
})
