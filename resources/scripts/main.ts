import '@awesome.me/webawesome/dist/components/icon/icon.js';
import * as webawesome from '@awesome.me/webawesome/dist/webawesome.js';

import '@/ui/Icon';
import '@/ui/Action';
import '@/ui/ActionDropdown';
import '@/ui/Actions';
import '@/ui/Dropdown';
import '@/ui/DropdownItem';

import '@/components/Modal';
import '@/components/PostForm';
import '@/components/Post';
import '@/components/Posts';
import '@/components/Profile';

import '@/pages/IndexPage';
import '@/pages/ProfilePage';
import '@/pages/ProfileSettingsPage';
import '@/pages/LoginPage';
import '@/pages/RegisterPage';

webawesome.registerIconLibrary('default', {
    resolver: (name) => {
        return `/icons/fontawesome-free/svgs-full/solid/${name}.svg`;
    },
});