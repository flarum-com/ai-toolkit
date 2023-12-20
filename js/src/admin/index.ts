import app from 'flarum/admin/app';

app.initializers.add('flarum-ai-toolkit', () => {

    app.extensionData
        .for('flarum-ai-toolkit')
        .registerSetting({
            setting: 'flarum-ai-toolkit.openai-api-key',
            label: app.translator.trans('flarum-ai-toolkit.admin.setting.api-key'),
            type: 'input',
        })
        .registerSetting({
            setting: 'flarum-ai-toolkit.openai-api-organisation',
            label: app.translator.trans('flarum-ai-toolkit.admin.setting.api-organisation'),
            type: 'input',
        });
});
