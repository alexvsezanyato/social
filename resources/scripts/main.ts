import '@awesome.me/webawesome/dist/components/icon/icon.js';
import * as webawesome from '@awesome.me/webawesome/dist/webawesome.js';

import '@/ui/XIcon';
import '@/ui/XAction';
import '@/ui/XActionDropdown';
import '@/ui/XActions';
import '@/ui/XDropdown';
import '@/ui/XDropdownItem';
import '@/ui/XBlock';

import '@/components/XModal';
import '@/components/XPostForm';
import '@/components/XPost';
import '@/components/XPosts';
import '@/components/XPostContent';
import '@/components/XPostHeader';
import '@/components/XPostComment';
import '@/components/XPostComments';
import '@/components/XPostCommentForm';
import '@/components/XProfile';
import '@/components/XDocuments';
import '@/components/XPictures';

import '@/pages/XIndexPage';
import '@/pages/XProfilePage';
import '@/pages/XProfileSettingsPage';
import '@/pages/XLoginPage';
import '@/pages/XRegisterPage';

webawesome.registerIconLibrary('default', {
    resolver: (name) => {
        return `/icons/fontawesome-free/svgs-full/solid/${name}.svg`;
    },
});