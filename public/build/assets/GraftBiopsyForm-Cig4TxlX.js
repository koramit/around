import{I as v}from"./CaseEdit-BpbIYVWm.js";import{_ as $}from"./FormDatetime-BOWnFbqf.js";import{_ as y}from"./FormCheckbox-CO8y-rzW.js";import{_ as U}from"./FormInput-wj6qOvCn.js";import{_ as B,I as x}from"./ImageUploader-Cglnpv-f.js";import{r as C,z as F,b as i,c as d,F as p,m as c,d as l,e as n,t as _,k as I,n as k}from"./app-Dc9PxFEA.js";import"./FormAutocomplete-B-uBCLSb.js";import"./throttle-BR4ffxXN.js";import"./FormSelect-D7Khl9nA.js";import"./FormRadio-Ck76tzjD.js";import"./useSelectOther-BPqg7jqa.js";import"./FormTextarea-BLFQThV3.js";import"./IconPaperclip-CUQfURoa.js";import"./SpinnerButton-0AbqMLaG.js";import"./useConfirmForm-kRRsCrcV.js";import"./IconEyesSlash-pjunoXev.js";import"./IconEyes-Bwms401q.js";const w={class:"grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8"},q={class:"space-y-2 md:space-y-4"},N={class:"form-label"},T=["id"],j={class:"mt-2 md:mt-4 grid grid-cols-2 gap-2 md:gap-4"},z=["onClick"],A=l("hr",{class:"border border-dashed my-2 md:my-4 xl:my-8"},null,-1),ee={__name:"GraftBiopsyForm",props:{modelValue:{type:Array,required:!0},configs:{type:Object,required:!0}},emits:["update:modelValue","autosave"],setup(r,{emit:f}){const b=r,u=f,o=C([...b.modelValue]);F(()=>o.value,s=>{u("update:modelValue",[...s]),u("autosave")},{deep:!0});function g(s){let m=[...o.value];m.splice(s,1),o.value=[],k(()=>{o.value=m})}return(s,m)=>(i(),d("div",null,[(i(!0),d(p,null,c(o.value,(t,e)=>(i(),d("div",{key:e,class:"my-2 md:my-4 space-y-2 md:space-y-4"},[l("div",w,[l("div",q,[n($,{label:`date of biopsy#${e+1}`,name:`graft_biopsies.${e}.date_biopsy`,modelValue:t.date_biopsy,"onUpdate:modelValue":a=>t.date_biopsy=a,error:s.$page.props.errors["graft_biopsies."+e+".date_biopsy"]},null,8,["label","name","modelValue","onUpdate:modelValue","error"]),l("div",null,[l("label",N,"result biopsy#"+_(e+1)+" :",1),l("small",{class:"form-error-block form-scroll-mt",id:`graft_biopsies.${e}.result`},_(s.$page.props.errors[`graft_biopsies.${e}.result`]),9,T),l("div",j,[(i(!0),d(p,null,c(r.configs.biopsy_result_fields,(a,h)=>(i(),I(y,{key:h,label:a.label,name:`graft_biopsies.${e}.result.${a.name}`,modelValue:t.result[a.name],"onUpdate:modelValue":V=>t.result[a.name]=V},null,8,["label","name","modelValue","onUpdate:modelValue"]))),128))]),n(U,{class:"mt-2 md:mt-4",name:`graft_biopsies.${e}.result.other_result`,modelValue:t.result.other_result,"onUpdate:modelValue":a=>t.result.other_result=a,placeholder:"other result"},null,8,["name","modelValue","onUpdate:modelValue"])])]),n(B,{label:`attachment biopsy#${e+1}`,"service-endpoints":r.configs.routes.upload,pathname:r.configs.attachment_upload_pathname,modelValue:t.attachment,"onUpdate:modelValue":a=>t.attachment=a},null,8,["label","service-endpoints","pathname","modelValue","onUpdate:modelValue"]),l("button",{class:"block",onClick:a=>g(e)},[n(x,{class:"w-4 h-4 text-red-400"})],8,z)]),A]))),128)),l("button",{onClick:m[0]||(m[0]=t=>o.value.push({...r.configs.graft_biopsy}))},[n(v,{class:"w-4 h-4 text-accent"})])]))}};export{ee as default};