import app from 'flarum/admin/app';

app.initializers.add('blomstra-ai-toolkit', () => {

    app.extensionData
        .for('blomstra-ai-toolkit')
        .registerSetting({
            setting: 'blomstra-ai-toolkit.openai-api-key',
            label: app.translator.trans('blomstra-ai-toolkit.admin.setting.api-key'),
            type: 'input',
        })
        .registerSetting({
            setting: 'blomstra-ai-toolkit.openai-api-organisation',
            label: app.translator.trans('blomstra-ai-toolkit.admin.setting.api-organisation'),
            type: 'input',
        });
});
