import fs from 'fs';
import path from 'path';

fs.cpSync(
    path.resolve('node_modules/@fortawesome/fontawesome-free'),
    path.resolve('public/icons/fontawesome-free'),
    {
        recursive: true,
    }
);