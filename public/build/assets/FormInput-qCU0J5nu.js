import{r as h,h as a,c as n,t as i,g as u,b as o,p as c}from"./app-Cw0y1MbD.js";const g={class:"w-full"},k=["for"],v={key:1,class:"flex"},p=["id","name","type","placeholder","pattern","readonly","value","disabled"],x={class:"w-auto flex items-center px-2 border-2 border-gray-200 rounded shadow-sm border-l-0 rounded-l-none bg-gray-50"},w={class:"inline-flex items-center"},$=["checked"],C={class:"ml-4 text-lg cursor-pointer whitespace-nowrap"},S=["id","name","type","placeholder","pattern","readonly","value","disabled"],V={key:3,class:"form-error-block"},I={__name:"FormInput",props:{modelValue:{type:[String,Number,null],default:""},modelCheckbox:{type:Boolean},name:{type:String,required:!0},label:{type:String,default:""},type:{type:String,default:"text"},placeholder:{type:String,default:""},pattern:{type:String,default:""},readonly:{type:Boolean},error:{type:String,default:""},switchLabel:{type:String,default:""},disabled:{type:Boolean}},emits:["autosave","update:modelValue","update:modelCheckbox"],setup(e,{expose:m,emit:y}){const s=y,r=h(null),b=()=>{r.value.focus()},f=t=>{s("update:modelCheckbox",t.target.checked),s("autosave")};return m({focus:b}),(t,l)=>(a(),n("div",g,[e.label?(a(),n("label",{key:0,class:"form-label",for:e.name},i(e.label)+" :",9,k)):u("",!0),e.switchLabel?(a(),n("div",v,[o("input",{id:e.name,name:e.name,ref_key:"input",ref:r,onInput:l[0]||(l[0]=d=>t.$emit("update:modelValue",t.$refs.input.value)),onChange:l[1]||(l[1]=d=>t.$emit("autosave")),type:e.type,placeholder:e.placeholder,pattern:e.pattern,readonly:e.readonly,value:e.modelValue,class:c(["form-input border-r-0 rounded-r-none form-scroll-mt",{"!border-red-400 !text-red-400":e.error}]),disabled:e.disabled},null,42,p),o("div",x,[o("label",w,[o("input",{type:"checkbox",class:"shadow-xs h-6 w-6 transition-all duration-200 ease-in-out appearance-none color inline-block align-middle border border-gray-400 select-none shrink-0 rounded cursor-pointer focus:outline-none",checked:e.modelCheckbox,onChange:f},null,40,$),o("span",C,i(e.switchLabel),1)])])])):(a(),n("input",{key:2,id:e.name,name:e.name,ref_key:"input",ref:r,onInput:l[2]||(l[2]=d=>t.$emit("update:modelValue",t.$refs.input.value)),onChange:l[3]||(l[3]=d=>t.$emit("autosave")),type:e.type,placeholder:e.placeholder,pattern:e.pattern,readonly:e.readonly,value:e.modelValue,class:c(["form-input form-scroll-mt",{"!border-red-400 !text-red-400":e.error}]),disabled:e.disabled},null,42,S)),e.error?(a(),n("div",V,i(e.error),1)):u("",!0)]))}};export{I as _};