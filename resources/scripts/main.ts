import '@awesome.me/webawesome/dist/components/icon/icon.js';
import * as webawesome from '@awesome.me/webawesome/dist/webawesome.js';

import './components/PostFormModal';
import './components/Modal';
import './components/PostForm';
import './components/Post';
import './components/Posts';

webawesome.registerIconLibrary('default', {
    resolver: (name) => {
        return `/fontawesome-free-7.1.0-web/svgs-full/solid/${name}.svg`;
    },
});