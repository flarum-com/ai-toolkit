(()=>{var t={n:e=>{var a=e&&e.__esModule?()=>e.default:()=>e;return t.d(a,{a}),a},d:(e,a)=>{for(var o in a)t.o(a,o)&&!t.o(e,o)&&Object.defineProperty(e,o,{enumerable:!0,get:a[o]})},o:(t,e)=>Object.prototype.hasOwnProperty.call(t,e),r:t=>{"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})}},e={};(()=>{"use strict";t.r(e);const a=flarum.core.compat["admin/app"];var o=t.n(a);o().initializers.add("blomstra-ai-toolkit",(function(){o().extensionData.for("blomstra-ai-toolkit").registerSetting({setting:"blomstra-ai-toolkit.openai-api-key",label:o().translator.trans("blomstra-ai-toolkit.admin.setting.api-key"),type:"input"}).registerSetting({setting:"blomstra-ai-toolkit.openai-api-organisation",label:o().translator.trans("blomstra-ai-toolkit.admin.setting.api-organisation"),type:"input"})}))})(),module.exports=e})();
//# sourceMappingURL=admin.js.map