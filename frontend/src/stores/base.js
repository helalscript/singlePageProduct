const base = {
  state() {
    return {
      url: 'http://127.0.0.1:8000/api/',
      imageLink: 'http://127.0.0.1:8000/',
    };
  },
  getters: {
    url(state) {
      return state.url;
    },
    imageLink(state){
      return state.imageLink;
    }
  },
};
export default base;
