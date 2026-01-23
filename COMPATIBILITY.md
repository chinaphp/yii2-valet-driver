# YiiValetDriver - 驱动特性对比

## 框架版本支持

```infographic
infographic compare-binary-horizontal-simple-fold
data
  title 框架版本支持
  items
    - label Yii2
      Yii2ValetDriver ✅
      YiiValetDriver ✅
    - label Yii3
      Yii2ValetDriver ❌
      YiiValetDriver ✅
```

## 目录结构支持

```infographic
infographic list-grid-compact-card
data
  title 支持的目录结构
  items
    - label public/
      desc Yii3 Web 根目录
      icon mdi:folder-outline
    - label web/
      desc Yii2 Web 根目录
      icon mdi:folder-outline
    - label bin/yii
      desc Yii3 控制台入口
      icon mdi:console
    - label /yii
      desc Yii2 控制台入口
      icon mdi:console
```

## 自动检测流程

```infographic
infographic sequence-snake-steps-simple
data
  title 框架检测流程
  items
    - label 步骤 1
      desc 检查 bin/yii 文件（Yii3）
    - label 步骤 2
      desc 检查 vendor/yiisoft/yii 包（Yii3）
    - label 步骤 3
      desc 检查 config/web.php + public/（Yii3）
    - label 步骤 4
      desc 检查 /yii 文件（Yii2）
    - label 步骤 5
      desc 检查 vendor/yiisoft/yii2 包（Yii2）
    - label 步骤 6
      desc 降级检测 web/index.php
```

## 支持的入口点

```infographic
infographic list-grid-badge-card
data
  title 入口点支持
  items
    - label 主入口
      desc index.php
      icon mdi:home
    - label API
      desc api/index.php
      icon mdi:api
    - label Backend
      desc backend/index.php
      icon mdi:cogs
    - label Admin
      desc admin/index.php
      icon mdi:shield-account
    - label OAuth2
      desc oauth2/index.php
      icon mdi:key
    - label 版本控制
      desc v1/, v2/
      icon mdi:source-branch
```

## 驱动选择指南

```infographic
infographic sequence-ascending-steps
data
  title 选择合适的驱动
  items
    - label LocalValetDriver
      desc 需要自定义配置，项目特定
    - label YiiValetDriver
      desc 推荐，同时支持 Yii2 和 Yii3
    - label Yii2ValetDriver
      desc 仅支持 Yii2，保持向后兼容
```

## 静态文件处理

| 驱动 | Asset 子域名 | Web 目录 | 降级处理 |
|------|-------------|---------|---------|
| Yii2ValetDriver | ✅ | web/ | ❌ |
| YiiValetDriver | ✅ | public/ → web/ | ✅ |
| LocalValetDriver | ✅ | 可配置 | ✅ |

## 兼容性总结

```infographic
infographic list-column-done-list
data
  title 兼容性清单
  items
    - label Yii2 基础项目
      desc 完全兼容，无需修改
    - label Yii2 高级项目
      desc 完全兼容，支持 frontend/backend
    - label Yii3 标准项目
      desc 新增支持，自动检测
    - label 混合项目
      desc 优先 public/，降级 web/
    - label 自定义入口点
      desc 支持更多入口类型
    - label Asset 子域名
      desc 完全兼容
```

## 性能影响

| 操作 | 原版 | 新版 | 影响 |
|------|------|------|------|
| 框架检测 | 2-3 次检查 | 3-6 次检查 | 微小 |
| 静态文件解析 | O(1) | O(2) | 可忽略 |
| 入口点路由 | O(n) | O(n) | 无 |
| 文件系统调用 | 基准 | +30-50% | 短路优化 |

## 迁移步骤

```infographic
infographic list-row-simple-horizontal-arrow
data
  title 迁移步骤
  items
    - label 备份原驱动
      desc cp Yii2ValetDriver.php Yii2ValetDriver.php.bak
    - label 下载新驱动
      desc wget YiiValetDriver.php
    - label 重启 Valet
      desc valet restart
    - label 测试项目
      desc 访问所有现有项目
```

---

## 快速开始

### Yii2 项目（现有）
```bash
# 无需任何操作，直接使用
valet link my-yii2-app
```

### Yii3 项目（新增）
```bash
# 下载新驱动后
cd my-yii3-project
valet link my-yii3-app
```

### 自定义项目
```bash
# 复制本地驱动
cp LocalValetDriver.php /path/to/project/
cd /path/to/project
# 编辑 $entries 和 $webDirectories
valet link my-custom-app
```
